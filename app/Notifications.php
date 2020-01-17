<?php

namespace App;

class Notifications
{
  public static function sendMonthlyActivity($id_playlist, $id_user, $type)
  {
    if(array_search($type, [ 'ans', 'skp', 'vid', 'ext' ]) === false) {
      throw new \InvalidArgumentException('Invalid activity type', 400);
    }

    app()->mdb->insert('playlist_counter', [
      'id_playlist' => $id_playlist,
      'id_user' => $id_user,
      'type' => $type,
      'date' => time(),
    ]);
  }


  public static function sendParentCode($id_playlist, $id_user, $type)
  {

  }
}
