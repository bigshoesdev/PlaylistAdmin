<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class UserMiddleware
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
    // file_put_contents('./test.log', $headers['Authorization']);
    $cookie = empty($headers['Authorization']) ? [] : json_decode(Crypt::decrypt($headers['Authorization']), true);
    if(!empty($cookie)) {
      if(isset($cookie['id_facebook'])) {
        $user = (new \App\Http\Controllers\FacebookController())->login(
          $cookie['id_facebook'],
          $cookie['token']
        );
      } else {
        $user = (new \App\Http\Controllers\UserController())->login(
          $cookie['id_user'],
          $cookie['pass']
        );
      }
    }

    if(!$user) {
      return response()->json([
        'error' => 'You are unlogged'
      ], 401);
    } else {
      $request->attributes = ['user' => $user];
      return $next($request);
    }
  }
}
