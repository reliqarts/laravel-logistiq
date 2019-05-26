<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Providers;

use ReliqArts\Logistiq\Tracking\Contracts\EventCreator as EventCreatorContract;
use ReliqArts\Logistiq\Tracking\Contracts\Tracker as TrackerContract;
use ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate as TrackingUpdateContract;
use ReliqArts\Logistiq\Tracking\Models\TrackingUpdate;
use ReliqArts\Logistiq\Tracking\Services\EventCreator;
use ReliqArts\Logistiq\Tracking\Services\Tracker;
use ReliqArts\ServiceProvider as IlluminateServiceProvider;

final class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->register(EventServiceProvider::class);
    }

    protected function registerBindings(): void
    {
        $this->app->singleton(
            EventCreatorContract::class,
            EventCreator::class
        );

        $this->app->singleton(
            TrackerContract::class,
            Tracker::class
        );

        $this->app->singleton(
            TrackingUpdateContract::class,
            TrackingUpdate::class
        );
    }
}
