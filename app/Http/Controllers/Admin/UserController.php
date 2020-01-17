<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class UserController extends \App\Http\Controllers\Controller
{


  public function get(Request $request, $id_user)
  {
    return app()->mdb->get('user', '*', [
      'id_user' => $id_user
    ]);
  }


  public function index(Request $request)
  {
    $users = app()->mdb->select('user', '*');

    foreach ($users as $i => $user) {

      $users[$i]['playlist_played'] = app()->mdb->count('playlist_counter', [
        'id_user' => $user['id_user'],
        'type' => 'ans',
      ]);
      $users[$i]['playlist_skipped'] = app()->mdb->count('playlist_counter', [
        'id_user' => $user['id_user'],
        'type' => 'skp',
      ]);
      $users[$i]['playlist_liked'] = app()->mdb->count('playlist_like', [
        'id_user' => $user['id_user'],
      ]);
      $users[$i]['shootlist_played'] = app()->mdb->count('shootlist_counter', [
        'id_user' => $user['id_user'],
        'type' => 'ans',
      ]);
      $users[$i]['shootlist_skipped'] = app()->mdb->count('shootlist_counter', [
        'id_user' => $user['id_user'],
        'type' => 'skp',
      ]);
      $users[$i]['shootlist_liked'] = app()->mdb->count('shootlist_like', [
        'id_user' => $user['id_user'],
      ]);
      $users[$i]['notification'] = app()->mdb->get('user_notification', [
        'email_code[Bool]',
        'email_dev[Bool]',
        'email_funzone[Bool]',
        'phone_code[Bool]',
        'phone_dev[Bool]',
        'phone_funzone[Bool]',
      ], [
        'id_user' => $user['id_user'],
      ]);
    }

    return $users;
  }


  public function updateNotifications(Request $request, $id_user)
  {
    app()->mdb->update('user_notification', $request->all(), [
      'id_user' => $id_user
    ]);
  }


  public function update(Request $request, $id_user)
  {
    $data = $request->all();
    unset($data['pass']);

    if($data['new_subscribe_flag']) {
      $data['subscribe'] = time();
      $data['subscribe_interval'] = $data['new_subscribe'];
    }

    if($data['new_subscribe_games_flag']) {
      $data['subscribe_games'] = $data['new_subscribe_games'];
    }

    unset($data['new_subscribe_games']);
    unset($data['new_subscribe_games_flag']);
    unset($data['new_subscribe_flag']);
    unset($data['new_subscribe']);

    app()->mdb->update('user', $data, [
      'id_user' => $id_user
    ]);
  }


  public function delete(Request $request, $id_user)
  {
    app()->mdb->delete('user', [
      'id_user' => $id_user
    ]);
  }
}
