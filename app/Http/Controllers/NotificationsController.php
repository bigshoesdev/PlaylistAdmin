<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;

class NotificationsController extends Controller
{
  public static function update(Request $request)
  {
    $data = $request->all();

    unset($data['id_user']);

    app()->mdb->update('user_notification', $data, [
      'id_user' => $request->attributes['user']['id_user'],
    ]);
  }


  public static function get(Request $request)
  {
    return app()->mdb->get('user_notification', [
      'email_funzone[Bool]',
      'email_dev[Bool]',
      'email_code[Bool]',
      'phone_funzone[Bool]',
      'phone_dev[Bool]',
      'phone_code[Bool]',
    ], [
      'id_user' => $request->attributes['user']['id_user'],
    ]);
  }
}
