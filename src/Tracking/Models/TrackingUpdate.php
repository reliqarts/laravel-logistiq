<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Models;

use ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate as TrackingUpdateContract;
use ReliqArts\Logistiq\Utility\Eloquent\TrackingUpdate as EloquentTrackingUpdate;

final class TrackingUpdate extends EloquentTrackingUpdate implements TrackingUpdateContract
{
    /**
     * @param string $trackableIdentifier
     * @param string $trackableType
     * @param string $statusIdentifier
     *
     * @return mixed
     */
    public function log(
        string $trackableIdentifier,
        string $trackableType,
        string $statusIdentifier
    ) {
        return parent::create([
            static::COLUMN_TRACKABLE_IDENTIFIER => $trackableIdentifier,
            static::COLUMN_TRACKABLE_TYPE => $trackableType,
            static::COLUMN_STATUS_IDENTIFIER => $statusIdentifier,
        ]);
    }
}
