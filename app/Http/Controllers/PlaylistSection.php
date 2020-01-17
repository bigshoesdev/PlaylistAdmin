<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Medoo\Medoo;
use Gregwar\Image\Image;

class PlaylistSectionController extends Controller
{


  public function store(Request $request)
  {
    app()->mdb->insert('school', [
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


  public function update(Request $request, $id_school)
  {
    $data = $request->all();
    unset($data['id_school']);
    app()->mdb->update('school', $data, [
      'id_school' => $id_school,
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('school', '*');
  }


  public function delete(Request $request, $id_school)
  {
    app()->mdb->delete('school', [
      'id_school' => $id_school
    ]);
  }


  public function search(Request $request)
  {
    return app()->mdb->select('school', '*', [
      'name[~]' => "%{$request->input('name')}%",
      'LIMIT' => 32,
    ]);
  }


  public function getSchoolPrizers(Request $request)
  {
    return app()->mdb->select('playlist', [
      '[>]school' => 'id_school',
    ], [
      'id_school',
      'school.name',
      'school.city',
      'points' => Medoo::raw('COUNT(id_school)'),
    ], [
      'GROUP' => 'id_school',
      'LIMIT' => 32,
      'id_school[!]' => null,
    ]);
  }
}
