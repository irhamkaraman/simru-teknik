<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Set to '*' to trust all proxies — required when deployed behind shared hosting
     * reverse proxies (Nginx, Apache) where the real client IP/scheme is forwarded
     * via X-Forwarded-For and X-Forwarded-Proto headers.
     *
     * This ensures Laravel generates https:// URLs for all assets (Filament, Livewire, etc.)
     * preventing Mixed Content browser errors on HTTPS production deployments.
     *
     * @var string|array<int, string>|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
