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
     * @return mixed|Trackable Usually a Trackable implementation.
     *                         However it may return an unexpected type if \Illuminate\Queue\SerializesModels trait
     *                         is used by subclass.
     *
     * @see \Illuminate\Queue\SerializesModels
     * @see \Illuminate\Contracts\Database\ModelIdentifier
     */
    public function getTrackable()
    {
        return $this->trackable;
    }
}
