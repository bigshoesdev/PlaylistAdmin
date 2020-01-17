<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
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
    if($request->attributes['user']['role'] != 'adm') {
      return response()->json([
        'error' => 'You are not admin'
      ], 401);
    } else {
      return $next($request);
    }
  }
}
