<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;

class PlaylistSectionController extends Controller
{


  public function store(Request $request)
  {
    app()->mdb->insert('category_playlist_section', [
      'name' => $request->input('name'),
    ]);
  }


  // public function get(Request $request, $id_school)
  // {
  //   return app()->mdb->get('school', '*', [
  //     'id_school' => $id_school
  //   ]);
  // }


  // public function select(Request $request)
  // {
  //   return app()->mdb->select('school', '*');
  // }


  public function update(Request $request, $id_section)
  {
    $data = $request->all();
    unset($data['id_section']);
    app()->mdb->update('category_playlist_section', $data, [
      'id_section' => $id_section,
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('category_playlist_section', '*');
  }


  public function delete(Request $request, $id_section)
  {
    app()->mdb->delete('category_playlist_section', [
      'id_section' => $id_section
    ]);
  }
}
