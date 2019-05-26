<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Events;

use ReliqArts\Logistiq\Tracking\Contracts\Status;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;

class StatusChanged extends Event
{
    /**
     * @var Status
     */
    private $previousStatus;

    /**
     * @var Status
     */
    private $status;

    /**
     * StatusChanged constructor.
     *
     * @param Trackable $trackable
     * @param Status    $previousStatus
     * @param Status    $newStatus
     */
    public function __construct(Trackable $trackable, Status $previousStatus, Status $newStatus)
    {
        parent::__construct($trackable);

        $this->previousStatus = $previousStatus;
        $this->status = $newStatus;
    }

    /**
     * @return Status
     */
    public function getPreviousStatus(): Status
    {
        return $this->previousStatus;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }
}
