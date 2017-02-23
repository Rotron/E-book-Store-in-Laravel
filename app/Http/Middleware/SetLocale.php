<?php

namespace App\Http\Middleware;

use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        switch($request->route('locale')) {
            case 'pr':
            \App::setLocale('pr');
            break;
        }

        return $next($request);
    }
}
