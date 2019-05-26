<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Events;

use Illuminate\Queue\SerializesModels;
use ReliqArts\Logistiq\Tracking\Contracts\Event as EventContract;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;

abstract class Event implements EventContract
{
    use SerializesModels;

    /**
     * @var Trackable
     */
    protected $trackable;

    /**
     * Event constructor.
     *
     * @param Trackable $trackable
     */
    public function __construct(Trackable $trackable)
    {
        $this->trackable = $trackable;
    }

    /**
     * @return Trackable
     */
    public function getTrackable(): Trackable
    {
        return $this->trackable;
    }
}
