<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

use ReliqArts\Logistiq\Tracking\Exceptions\StatusChangeFailed;

interface Tracker
{
    /**
     * @param Trackable $trackable
     *
     * @return Status
     */
    public function getTrackableStatus(Trackable $trackable): Status;

    /**
     * @param Trackable $trackable
     * @param Status    $status
     *
     * @throws StatusChangeFailed
     */
    public function setTrackableStatus(Trackable $trackable, Status $status): void;
}
