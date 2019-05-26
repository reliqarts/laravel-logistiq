<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseServiceProvider;
use ReliqArts\Logistiq\Tracking\Events\StatusChanged;
use ReliqArts\Logistiq\Tracking\Listeners\Loudmouth;

final class EventServiceProvider extends BaseServiceProvider
{
    protected $listen = [
        StatusChanged::class => [
            Loudmouth::class,
        ],
    ];
}
