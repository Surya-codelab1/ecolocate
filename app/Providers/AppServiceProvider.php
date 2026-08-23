<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Render (and similar platforms) terminate HTTPS at the proxy level,
        // so Laravel only sees plain HTTP internally. Without this, asset()/@vite
        // and generated URLs default to http://, causing "Mixed Content" errors
        // in the browser since the page itself is served over https://.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}