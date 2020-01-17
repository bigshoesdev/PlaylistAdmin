<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class PromoController extends \App\Http\Controllers\Controller
{


  public function store(Request $request)
  {

    if($request->input('type') == 'games') {
      $type = $request->input('games') . ' ' . $request->input('type');
    } else {
      $type = $request->input('type');
    }

    app()->mdb->insert('promo', [
      'value' => $request->input('value'),
      'client' => $request->input('client'),
      'type' => $type,
    ]);
  }


  public function update(Request $request, $id_promo)
  {
    app()->mdb->update('promo', [
      'value' => $request->input('value'),
    ], [
      'id_promo' => $id_promo,
    ]);
  }


  public function delete($id_promo)
  {
    app()->mdb->delete('promo', [
      'id_promo' => $id_promo,
    ]);
  }


  public function index()
  {
    return app()->mdb->select('promo', '*');
  }
}
