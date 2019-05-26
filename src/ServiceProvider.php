<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use ReliqArts\Logistiq\Tracking\Providers\ServiceProvider as TrackingServiceProvider;
use ReliqArts\Logistiq\Utility\ServiceProvider as UtilityServiceProvider;

final class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        $this->app->register(UtilityServiceProvider::class);
        $this->app->register(TrackingServiceProvider::class);
    }
}
