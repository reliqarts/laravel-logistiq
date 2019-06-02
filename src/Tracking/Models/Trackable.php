<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Models;

use Illuminate\Support\Collection;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable as TrackableContract;
use ReliqArts\Logistiq\Utility\Eloquent\Trackable as EloquentTrackable;

abstract class Trackable extends EloquentTrackable implements TrackableContract
{
    /**
     * @return Collection
     */
    public function getTrackingUpdates(): Collection
    {
        return $this->trackingUpdates;
    }
}
