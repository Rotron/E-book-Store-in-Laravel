<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

// Redirect home if user is not admin.
class CheckIfAdmin
{
  const ADMIN = 1;
  const USER = 2;

  public function handle($request, Closure $next)
  {

    if(Auth::user()->role != self::ADMIN) {
      return redirect('/');
    }
    return $next($request);
  }
}
