<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;

class PlaylistSettingsController extends Controller
{
  const PATH = '../../../public/uploads/img/';//'./../storage/app/playlist/';
  const URL = '/uploads/img/';//'/storage/playlist/';

  public function store(Request $request)
  {
    app()->mdb->insert('setting_playlist', [
      'name' => $request->input('name'),
      'type' => $request->input('type'),
      'date' => time(),
    ]);
  }


  public function get(Request $request, $id_setting)
  {
    return app()->mdb->get('setting_playlist', '*', [
      'id_setting' => $id_setting
    ]);
  }


  public function select(Request $request, $type = null)
  {
    if(!empty($type)) {
      return app()->mdb->select('setting_playlist', '*', [
        'type' => $type
      ]);
    } else {
      return app()->mdb->select('setting_playlist', '*');
    }

  }


  public function update(Request $request, $id_setting)
  {
    $data = $request->all();
    unset($data['id_setting']);
    app()->mdb->update('setting_playlist', $data, [
      'id_setting' => $id_setting,
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('setting_playlist', '*');
  }


  public function delete(Request $request, $id_setting)
  {
    app()->mdb->delete('setting_playlist', [
      'id_setting' => $id_setting
    ]);
  }

  public function setImage(Request $request, $id_setting)
  {
    $name = str_random(16);
    $file = Image::open($request->file('file')->getPathName());

    $prev = app()->mdb->get('setting_playlist', 'img', [
      'id_setting' => $id_setting,
    ]);

    if(!empty($prev)) {
      $_ = explode('/', $prev);
      $tmp_path = self::PATH . end($_);
      if(file_exists($tmp_path) && is_file($tmp_path)) {
        unlink($tmp_path);
      }
    }

    $file
      ->fill(0xffffff)
      ->cropResize(128, 128)
      ->save(self::PATH . $name, 'png');

    app()->mdb->update('setting_playlist', [
      'img' => $_ENV['APP_URL'] . self::URL . $name,
    ], [
      'id_setting' => $id_setting
    ]);

    return [
      'url' => $_ENV['APP_URL'] . self::URL . $name
    ];
  }
}
