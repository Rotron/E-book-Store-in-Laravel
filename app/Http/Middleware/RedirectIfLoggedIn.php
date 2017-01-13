<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfLoggedIn
{
    public function handle($request, Closure $next)
    {
      if (Auth::check()) {
        return redirect('admin/admincp');
      }
      return $next($request);
    }
}
