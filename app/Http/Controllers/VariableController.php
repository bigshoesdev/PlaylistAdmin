<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VariableController extends Controller
{

  public function get(Request $request, $name)
  {

    $var = app()->mdb->get('variable', '*', [
      'name' => $name,
    ]);

    if(array_search($var['name'], [ 'prices' ]) !== false) {
      $var['value'] = json_decode($var['value'], true);
    }

    return $var;
  }

}
