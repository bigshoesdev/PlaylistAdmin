<?php

namespace App;

class File
{
    public static function delete($id_file)
    {
      $tmp_name = app()->mdb->get('file_tmp', 'tmp_name', [
        'id_file' => $id_file
      ]);
      unlink('./../storage/app/tmp/' . $tmp_name);
      app()->mdb->delete('file_tmp', [
        'id_file' => $id_file
      ]);
    }


    public static function move($id_file, $path)
    {
      $tmp_name = app()->mdb->get('file_tmp', 'tmp_name', [
        'id_file' => $id_file
      ]);
      rename(
        './../storage/app/tmp/' . $tmp_name,
        './../storage/app/' . trim($path, '/')
      );
      app()->mdb->delete('file_tmp', [
        'id_file' => $id_file
      ]);
    }
}
