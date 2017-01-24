<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

// Redirect guests to home..
class RedirectGuest
{
    public function handle($request, Closure $next)
    {
      if (Auth::guest()) {
        return redirect('/');
      }
      return $next($request);
    }
}
