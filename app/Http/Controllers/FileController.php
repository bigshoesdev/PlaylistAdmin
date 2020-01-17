<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use RuntimeException;
use Illuminate\Http\Request;

class FileController extends Controller
{


  public function attachFile(Request $request)
  {
    $file = $request->file('file');
    $name = $file->getClientOriginalName();
    $tmp_name = str_random(32);

    if(mb_strlen($name) > 128)
      throw new InvalidArgumentException('File name is too long, no more than 128 symbols');

    $file->move('./../storage/app/tmp', $tmp_name);

    app()->mdb->insert('file_tmp', [
      'original_name' => $name,
      'tmp_name' => $tmp_name,
      'date' => time(),
    ]);

    return [ 'id' => app()->mdb->id() ];
  }
}
