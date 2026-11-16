<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Force HTTPS for all generated URLs when behind a reverse proxy on production.
        // This fixes Mixed Content errors where Filament/Livewire assets are served over HTTP
        // even though the site is accessed over HTTPS (e.g. simru-teknik.miraigrid.web.id).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
