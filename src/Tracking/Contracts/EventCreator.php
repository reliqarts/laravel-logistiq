<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

use ReliqArts\Logistiq\Tracking\Exceptions\TrackableEventCreationFailed;

interface EventCreator
{
    /**
     * @param string    $eventClassName
     * @param Trackable $trackable
     *
     * @throws TrackableEventCreationFailed
     *
     * @return mixed
     */
    public function createForTrackable(string $eventClassName, Trackable $trackable);
}
