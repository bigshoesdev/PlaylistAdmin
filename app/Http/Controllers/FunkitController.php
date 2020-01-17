<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;

class FunkitController extends Controller
{
  const PATH = './../storage/app/funkit/';
  const URL = '/storage/funkit/';


  public function store(Request $request)
  {
    app()->mdb->insert('funkit', [
      'name' => $request->input('name'),
      'date' => time(),
    ]);
  }


  public function get(Request $request, $id_funkit)
  {
    return app()->mdb->get('funkit', '*', [
      'id_funkit' => $id_funkit
    ]);
  }


  public function select(Request $request)
  {
    return app()->mdb->select('funkit', '*', [
      'show' => true,
      'ORDER' => [ 'order_index' => 'ASC' ],
    ]);
  }


  public function buy(Request $request, $id_funkit)
  {

    if(empty($request->input('city')))
      throw new \InvalidArgumentException(
        'להיכנס לעיר',
        400
      );
    if(empty($request->input('street')))
      throw new \InvalidArgumentException(
        'הזן את הרחוב',
        400
      );
    if(empty($request->input('num')))
      throw new \InvalidArgumentException(
        'הזן מספר',
        400
      );
    if(empty($request->input('mail')))
      throw new \InvalidArgumentException(
        'הזן דוא"ל',
        400
      );
    if(empty($request->input('postal')))
      throw new \InvalidArgumentException(
        'הזן את הדואר',
        400
      );

    $item = app()->mdb->get('funkit', '*', [
      'id_funkit' => $id_funkit
    ]);

    $email = $request->attributes['user']['email'];
    $name = "{$request->attributes['user']['name']} {$request->attributes['user']['surname']}";

    $data = (new \App\PaymentProvider())->getUrl(35 + 17, $item['name'], '/funkit/callback', $name, $email);

    app()->mdb->insert('transaction', [
      'code' => $data['LowProfileCode'],
      'id_user' => $request->attributes['user']['id_user'],
      'data' => json_encode(array_merge($request->all(), [
        'id_funkit' => $id_funkit,
      ]), JSON_UNESCAPED_UNICODE),
      'date' => time(),
    ]);

    return [
      'url' => $data['url'],
      'id_transaction' => app()->mdb->id(),
    ];
  }


  public function buyCallback(Request $request)
  {
    $trans = (new \App\PaymentProvider())->onCallback($request);

    $user = app()->mdb->get('user', '*', [
      'id_user' => $trans['id_user'],
    ]);

    $user = $user['name'] . ' ' . $user['surname'] . ' ID: ' . $user['id_user'];

    $funkit = app()->mdb->get('funkit', 'name', [
      'id_funkit' => $trans['data']['id_funkit'],
    ]);

    app()->mdb->update('funkit', [
      'requests[+]' => 1
    ], [
      'id_funkit' => $trans['data']['id_funkit'],
    ]);

    mail(env('APP_RELEASE_EMAIL'), 'Funkit buyer', "
      user: $user \n
      funkit: $funkit \n
      city: {$trans['data']['city']} \n
      street: {$trans['data']['street']} \n
      num: {$trans['data']['num']} \n
      mail: {$trans['data']['mail']} \n
      postal: {$trans['data']['postal']} \n
      comment: {$trans['data']['comment']}
    ");
  }


  public function update(Request $request, $id_funkit)
  {
    $color = $request->input('color');
    if(empty($color)) {
      $color = '#C286FF';
    }

    app()->mdb->update('funkit', [
      'name' => $request->input('name'),
      'text' => $request->input('text'),
      'color' => $color,
      'show' => $request->input('show'),
      'order_index' => $request->input('order_index'),
    ], [
      'id_funkit' => $id_funkit,
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('funkit', [
      'id_funkit',
      'name',
      'text',
      'color',
      'show[Bool]',
      'date[Int]',
      'order_index',
      'requests',
    ], [
      'ORDER' => [ 'order_index' => 'ASC' ]
    ]);
  }


  public function setImage(Request $request, $id_funkit)
  {
    $name = str_random(16);
    $file = $request->file('file');

    $prev = app()->mdb->get('funkit', 'img', [
      'id_funkit' => $id_funkit,
    ]);

    if(!empty($prev)) {
      $_ = explode('/', $prev);
      $tmp_path = self::PATH . end($_);
      if(file_exists($tmp_path) && is_file($tmp_path))
        unlink($tmp_path);
    }

    $file->move(self::PATH,  $name);

    app()->mdb->update('funkit', [
      'img' => $_ENV['APP_URL'] . self::URL . $name,
    ], [
      'id_funkit' => $id_funkit
    ]);
  }


  public function delete(Request $request, $id_funkit)
  {
    $img = app()->mdb->get('funkit', 'img', [
      'id_funkit' => $id_funkit,
    ]);

    if(!empty($img)) {
      $_ = explode('/', $img);
      unlink(self::PATH . end($_));
    }

    app()->mdb->delete('funkit', [
      'id_funkit' => $id_funkit
    ]);
  }
}
