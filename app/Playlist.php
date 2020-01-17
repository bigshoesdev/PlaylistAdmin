<?php

namespace App;

class Playlist
{
  public static function writeActivity($id_playlist, $id_user, $type)
  {
    app()->mdb->insert('playlist_counter', [
      'id_playlist' => $id_playlist,
      'id_user' => $id_user,
      'type' => $type,
      'date' => time(),
    ]);
  }
}
