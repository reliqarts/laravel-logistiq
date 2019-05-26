<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Exceptions;

use ReliqArts\Logistiq\Tracking\Contracts\Status;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;

final class StatusChangeFailed extends Exception
{
    /**
     * @var null|Status
     */
    private $status;

    /**
     * @param Trackable $trackable
     * @param Status    $targetStatus
     *
     * @return StatusChangeFailed
     */
    public static function forTrackable(Trackable $trackable, Status $targetStatus): self
    {
        $message = sprintf(
            'Status change failed for trackable (%s). Target status: %s',
            (string)$trackable->getIdentifier(),
            $targetStatus->getName()
        );
        $instance = new self($message);

        $instance->trackable = $trackable;
        $instance->status = $targetStatus;

        return $instance;
    }

    /**
     * @return null|Status
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }
}
