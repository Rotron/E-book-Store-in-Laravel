<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AlreadyPurchased
{
    public function handle($request, Closure $next)
    {
      // Make sure user doesnt try purchasing more than ones despite hidden button
      $id = (int) $request->route('id');

      if (Auth::user()->orders()->where('listing_id', $id)->first() != null) {
        throw new \Exception('User tried to purchase same product again');
      }

      return $next($request);
    }
}
