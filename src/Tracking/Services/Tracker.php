<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Services;

use ReliqArts\Logistiq\Tracking\Contracts\Status;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use ReliqArts\Logistiq\Tracking\Contracts\Tracker as TrackerContract;
use ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate;
use ReliqArts\Logistiq\Tracking\Events\StatusChanged;
use ReliqArts\Logistiq\Tracking\Exceptions\StatusChangeFailed;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher;

final class Tracker implements TrackerContract
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var TrackingUpdate
     */
    private $trackingUpdate;

    /**
     * Tracker constructor.
     *
     * @param EventDispatcher $eventDispatcher
     * @param TrackingUpdate  $trackingUpdate
     */
    public function __construct(EventDispatcher $eventDispatcher, TrackingUpdate $trackingUpdate)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->trackingUpdate = $trackingUpdate;
    }

    /**
     * @param Trackable $trackable
     *
     * @return Status
     */
    public function getTrackableStatus(Trackable $trackable): Status
    {
        return $trackable->getStatus();
    }

    /**
     * @param Trackable $trackable
     * @param Status    $status
     *
     * @throws StatusChangeFailed
     */
    public function setTrackableStatus(Trackable $trackable, Status $status): void
    {
        $oldStatus = $trackable->getStatus();

        if (!$trackable->setStatus($status)) {
            throw StatusChangeFailed::forTrackable($trackable, $status);
        }

        $this->registerStatusChange($trackable, $oldStatus, $status);
    }

    /**
     * @param Trackable $trackable
     * @param Status    $oldStatus
     * @param Status    $status
     */
    private function registerStatusChange(Trackable $trackable, Status $oldStatus, Status $status): void
    {
        $this->eventDispatcher->dispatch(new StatusChanged($trackable, $oldStatus, $status));
        $this->trackingUpdate->log(
            (string)$trackable->getIdentifier(),
            get_class($trackable),
            (string)$status->getIdentifier()
        );
    }
}
