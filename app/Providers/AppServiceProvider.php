<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force HTTPS for all generated URLs when APP_URL is configured with https://.
        // This approach is more reliable than checking APP_ENV=production because it
        // works regardless of the environment name set on the server.
        //
        // Required for shared hosting / cPanel deployments where Laravel sits behind a
        // reverse proxy that terminates SSL — without this, Filament/Livewire assets are
        // generated with http://, blocked by the browser as Mixed Content.
        //
        // To activate: set APP_URL=https://simru-teknik.miraigrid.web.id in .env
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
