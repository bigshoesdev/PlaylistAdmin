<?php

namespace App;

class User
{


  public static function getSafe($user)
  {

    if(!is_array($user)) {
      $data = app()->mdb->get('user', '*', [
        'id_user' => $user
      ]);
    } else {
      $data = $user;
    }

    unset($data['pass']);
    unset($data['token']);
    unset($data['id_facebook']);

    $data['subscribe'] = intval($data['subscribe']);
    $data['subscribe_interval'] = intval($data['subscribe_interval']);
    $data['subscribe_games'] = intval($data['subscribe_games']);
    $data['date'] = intval($data['date']);

    return $data;
  }


  public static function hasSubscribe($user)
  {
    return $user['subscribe'] + $user['subscribe_interval'] > time() || intval($user['subscribe_games']);
  }


  public static function hasSubscribeTime($user)
  {
    return $user['subscribe'] + $user['subscribe_interval'] > time();
  }
}
