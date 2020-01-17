<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class ParentMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $headers = apache_request_headers();
    $code = empty($headers['Parent-Authorization']) ? '' : $headers['Parent-Authorization'];

    $answer = (new \App\Http\Controllers\ParentController())->login($request->attributes['user']['id_user'], $code);

    if($answer) {
      return $next($request);
    } else {
      return response()->json([
        'error' => 'Auth as parent, please'
      ], 401);
    }
  }
}
