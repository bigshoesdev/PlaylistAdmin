<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class ParentController extends \App\Http\Controllers\Controller
{


  public function get(Request $request, $id_user)
  {
    // return app()->mdb->select('parent_code' '*', [
    //   'id_user' => $id_user,
    // ]);
  }


  public function getGifts(Request $request, $id_user)
  {

    return app()->mdb->select('user_category', [
      '[>]category_playlist'
    ], '*', [
      'id_user' => $id_user
    ]);
  }


  public function sendPass(Request $request, $id_user)
  {

    $user = app()->mdb->get('user', '*', [
      'id_user' => $id_user
    ]);

    $code = app()->mdb->get('parent_code', 'code', [
      'id_user' => $id_user,
    ]);

    if(!empty($user['phone'])) {
      (new \App\SMSProvider())->sendSms("Your pin is " . $code, $user['phone']);
    } else {
      mail($user['email'], 'Parent pin', $code);
    }
  }


  public function index(Request $request)
  {
    $users = app()->mdb->select('parent_code', [
      '[>]user' => 'id_user',
    ], '*');

    return $users;
  }


  // public function delete(Request $request)
  // {
  //   app()->mdb->delete('parent_code', [
  //     '[>]user' => 'id_user',
  //   ], '*');
  // }
}
