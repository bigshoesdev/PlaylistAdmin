<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class PricesController extends \App\Http\Controllers\Controller
{


  public function update(Request $request)
  {
    app()->mdb->update('variable', [
      'value[JSON]' => $request->input('prices'),
    ], [
      'name' => 'prices',
    ]);
  }
  

  public function get()
  {
    return app()->mdb->get('variable', [
      'id_variable',
      'name',
      'value[JSON]',
    ], [
      'name' => 'prices',
    ]);
  }
}
