<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class VariableController extends \App\Http\Controllers\Controller
{


  public function index()
  {
    return app()->mdb->select('variable', '*', [
      'name[!]' => [ 'prices', 'tutorial' ]
    ]);
  }


  public function update(Request $request, $id_variable)
  {
    app()->mdb->update('variable', [
      'value' => $request->input('value'),
    ], [
      'id_variable' => $id_variable,
    ]);
  }
}
