<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class ParentController extends Controller
{
  public function newParentCode(Request $request)
  {
    $code = $request->input('code');

    if($code > 9999) {
      throw new \InvalidArgumentException(
        'סיכה ארוכה מדי, לא יותר מ 4 ספרות',
        400
      );
    }
    if(!is_numeric($code)) {
      throw new \InvalidArgumentException(
        'פין חייב להיות מספר',
        400
      );
    }

    if(app()->mdb->has('parent_code', [
      'id_user' => $request->attributes['user']['id_user'],
    ])) {
      app()->mdb->update('parent_code', [
        'code' => $code,
      ], [
        'id_user' => $request->attributes['user']['id_user']
      ]);
    } else {
      app()->mdb->insert('parent_code', [
        'code' => $code,
        'id_user' => $request->attributes['user']['id_user'],
      ]);
    }
  }


  public function sendFirstCode(Request $request)
  {
    mail($request->attributes['user']['email'], 'Parent pin', app()->mdb->get('parent_code', 'code', [
      'id_user' => $request->attributes['user']['id_user'],
    ]));
  }


  public function sendSecondCode(Request $request)
  {

    if(empty($request->attributes['user']['phone']))
      throw new \InvalidArgumentException(
        'לא הגדרת טלפון',
        400
      );


    // $basic  = new \Nexmo\Client\Credentials\Basic('d62cb964', 'wQUgmZ6eznQPlrWS');
    // $client = new \Nexmo\Client($basic);
    //
    // $message = $client->message()->send([
    //     'to' => '380932502176',
    //     'from' => 'Playlist',
    //     'text' => app()->mdb->get('parent_code', 'code', [
    //       'id_user' => $request->attributes['user']['id_user'],
    //     ])
    // ]);

    $code = app()->mdb->get('parent_code', 'code', [
      'id_user' => $request->attributes['user']['id_user'],
    ]);

    (new \App\SMSProvider())->sendSms("Your pin is " . $code, $request->attributes['user']['phone']);
  }


  public function login($id_user, $code)
  {
    return app()->mdb->has('parent_code', [
      'id_user' => $id_user,
      'code' => $code,
    ]) || !app()->mdb->has('parent_code', [
      'id_user' => $id_user,
    ]);
  }


  public function hasPin(Request $request)
  {
    return [
      'status' => app()->mdb->has('parent_code', [
        'id_user' => $request->attributes['user']['id_user'],
      ]),
    ];
  }


  public function parentLogin(Request $request)
  {
    if(!$this->login($request->attributes['user']['id_user'], $request->input('code'))) {
      throw new \InvalidArgumentException('סיכה שגויה');
    }
  }
}
