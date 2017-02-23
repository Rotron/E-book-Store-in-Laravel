<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \URL::macro('currentRouteWithLocale', function(string $locale, bool $absolute = true) {
            /** @var \Illuminate\Routing\Route $route */
            $route = $this->request->route();

            $parameters = $route->parameters();
            $parameters['locale'] = $locale;

            return $this->toRoute($route, $parameters, $absolute);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
