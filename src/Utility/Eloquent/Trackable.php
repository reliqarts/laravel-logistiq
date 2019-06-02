<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Eloquent;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property Collection $trackingUpdates
 */
class Trackable extends Model
{
    /**
     * @return HasMany
     */
    public function trackingUpdates(): HasMany
    {
        return $this->hasMany(
            TrackingUpdate::class,
            TrackingUpdate::COLUMN_TRACKABLE_IDENTIFIER
        )->where(TrackingUpdate::COLUMN_TRACKABLE_TYPE, static::class);
    }
}
