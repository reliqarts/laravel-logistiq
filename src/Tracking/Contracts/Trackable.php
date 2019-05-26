<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

use Illuminate\Support\Collection;

interface Trackable extends Identifiable
{
    /**
     * @return Status
     */
    public function getStatus(): Status;

    /**
     * @param Status $status
     *
     * @return bool
     */
    public function setStatus(Status $status): bool;

    /**
     * @return Collection Update history. A collection of objects implementing `TrackingUpdate`
     *
     * @see \ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate
     */
    public function getTrackingUpdates(): Collection;
}
