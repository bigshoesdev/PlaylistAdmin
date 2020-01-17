<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;

class WorkshopController extends Controller
{

  const PATH = './../storage/app/workshop/';
  const URL = '/storage/workshop/';


  public function store(Request $request)
  {
    app()->mdb->insert('workshop', [
      'name' => $request->input('name'),
      'date' => time(),
    ]);
  }


  public function get(Request $request, $id_workshop)
  {
    return app()->mdb->get('workshop', '*', [
      'id_workshop' => $id_workshop,
    ]);
  }


  public function select(Request $request)
  {
    return app()->mdb->select('workshop', '*', [
      'show' => true,
      'ORDER' => [
        'order_index' => 'ASC',
      ]
    ]);
  }


  public function update(Request $request, $id_workshop)
  {
    app()->mdb->update('workshop', [
      'name' => $request->input('name'),
      'text' => $request->input('text'),
      'show' => $request->input('show'),
      'order_index' => $request->input('order_index'),
    ], [
      'id_workshop' => $id_workshop,
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('workshop', [
      'id_workshop',
      'name',
      'text',
      'show[Bool]',
      'date[Int]',
      'order_index',
      'requests',
    ], [
      'ORDER' => [
        'order_index' => 'ASC',
      ]
    ]);
  }


  public function form(Request $request, $id_workshop)
  {

    $workshop = app()->mdb->get('workshop', 'name', [
      'id_workshop' => $id_workshop,
    ]);

    app()->mdb->update('workshop', [
      'requests[+]' => 1
    ], [
      'id_workshop' => $id_workshop,
    ]);

    mail(env('APP_RELEASE_EMAIL'), 'Workshop requesting form.', "
      Workshop: $workshop \n
      Location: {$request->input('loc')} \n
      City: {$request->input('city')} \n
      Phone: {$request->input('phone')} \n
      Email: {$request->input('email')} \n
      Number of players: {$request->input('num')} \n
      Comment: {$request->input('comment')} \n
    ");
  }


  public function setImage(Request $request, $id_workshop)
  {
    $file = Image::open($request->file('file')->getPathName());

    $name = str_random(16) . '.jpg';

    $prev = app()->mdb->get('workshop', 'img', [
      'id_workshop' => $id_workshop,
    ]);

    if(!empty($prev)) {
      $_ = explode('/', $prev);
      unlink(self::PATH . end($_));
    }

    $file
      ->fill(0xffffff)
      ->cropResize(512, 256)
      ->save(self::PATH . $name, 'jpg');

    app()->mdb->update('workshop', [
      'img' => $_ENV['APP_URL'] . self::URL . $name,
    ], [
      'id_workshop' => $id_workshop
    ]);
  }


  public function delete(Request $request, $id_workshop)
  {
    $img = app()->mdb->get('workshop', 'img', [
      'id_workshop' => $id_workshop,
    ]);

    if(!empty($img)) {
      $_ = explode('/', $img);
      unlink(self::PATH . end($_));
    }

    app()->mdb->delete('workshop', [
      'id_workshop' => $id_workshop
    ]);
  }
}
