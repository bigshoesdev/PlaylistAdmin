<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class SubscribeController extends Controller
{


  public function usePromo(Request $request)
  {
    if(!app()->mdb->has('promo', [
      'value' => $request->input('promo'),
    ])) {
      throw new \InvalidArgumentException('קוד לא תקין');
    }

    $promo = app()->mdb->get('promo', '*', [
      'value' => $request->input('promo'),
    ]);

    if($promo['type'] == 'month') {
      app()->mdb->update('user', [
        'subscribe' => time(),
        'subscribe_interval' => 2.62 * pow(10, 6),
      ], [
        'id_user' => $request->attributes['user']['id_user'],
      ]);
    }
    else if($promo['type'] == 'six') {
      app()->mdb->update('user', [
        'subscribe' => time(),
        'subscribe_interval' => 1.57 * pow(10, 7),
      ], [
        'id_user' => $request->attributes['user']['id_user'],
      ]);
    }
    else {

      $games_to_add = explode(' ', $promo['type'])[0];

      $current_games = app()->mdb->get('user', 'subscribe_games', [
        'id_user' => $request->attributes['user']['id_user'],
      ]);

      $query = [ 'subscribe_games[+]' => $games_to_add ];

      if($current_games > 0) {
        $query['subscribe_total_games[+]'] = $games_to_add;
      } else {
        $query['subscribe_total_games'] = $games_to_add;
      }

      app()->mdb->update('user', $query, [
        'id_user' => $request->attributes['user']['id_user'],
      ]);
    }

    app()->mdb->update('promo', [
      'used[+]' => 1,
    ], [
      'value' => $request->input('promo'),
    ]);
  }


  public function getUrl(Request $request, $type)
  {

    $prices = json_decode(app()->mdb->get('variable', 'value', [
      'name' => 'prices',
    ]), true);

    if($type == 'games') {
      $price = $prices['games'];
      $type_name = $prices['games_num'] . ' Playlist games';
    }
    else if($type == 'month') {
      $price = $prices['month'];
      $type_name = 'One month subscribe';
    }
    else if($type == 'six') {
      $price = $prices['six'];
      $type_name = 'Six months subscribe';
    }
    else {
      throw new \InvalidArgumentException('Invalid type');
    }

    $email = $request->attributes['user']['email'];
    $name = "{$request->attributes['user']['name']} {$request->attributes['user']['surname']}";

    $provider = new \App\PaymentProvider();
    $data = $provider->getUrl($price, $type_name, '/subscribe/callback', $name, $email);

    app()->mdb->insert('transaction', [
      'code' => $data['LowProfileCode'],
      'id_user' => $request->attributes['user']['id_user'],
      'data' => json_encode([ 'type' => $type, 'type_name' => $type_name ]),
      'date' => time(),
    ]);

    return [
      'url' => $data['url'],
      'id_transaction' => app()->mdb->id(),
    ];
  }


  public function callback(Request $request)
  {
    $trans = (new \App\PaymentProvider())->onCallback($request);

    $type = $trans['data']['type'];

    if($type == 'games') {

      $games_to_add = json_decode(app()->mdb->get('variable', 'value', [
        'name' => 'prices',
      ]), true)['games_num'];

      $current_games = app()->mdb->get('user', 'subscribe_games', [
        'id_user' => $trans['id_user'],
      ]);

      $query = [ 'subscribe_games[+]' => $games_to_add ];

      if($current_games > 0) {
        $query['subscribe_total_games[+]'] = $games_to_add;
      } else {
        $query['subscribe_total_games'] = $games_to_add;
      }

      app()->mdb->update('user', $query, [
        'id_user' => $trans['id_user'],
      ]);
    } else {

      if($type == 'month') {
        $time = 2.62 * pow(10, 6);
      }
      else if($type == 'six') {
        $time = 1.57 * pow(10, 7);
      }
      else {
        throw new \RuntimeException('Subscription type ' . $type . ' is not valid');
      }

      app()->mdb->update('user', [
        'subscribe' => time(),
        'subscribe_interval' => $time,
      ], [
        'id_user' => $trans['id_user'],
      ]);
    }

    $user = app()->mdb->get('user', '*', [
      'id_user' => $trans['id_user'],
    ]);

    mail(env('APP_RELEASE_EMAIL'), 'Subscription bought', "
    Type: {$trans['data']['type_name']}, \n
    From: {$user['name']} {$user['surname']} (ID: {$user['id_user']}) \n
    ");
  }

}
