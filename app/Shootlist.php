<?php

namespace App;

class Shootlist
{
  public static function writeActivity($id_shootlist, $id_user, $type)
  {
    app()->mdb->insert('shootlist_counter', [
      'id_shootlist' => $id_shootlist,
      'id_user' => $id_user,
      'type' => $type,
      'date' => time(),
    ]);
  }
}
