<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Events;

use Spatie\EventProjector\ShouldBeStored;

abstract class StoredEvent extends Event implements ShouldBeStored
{
}
