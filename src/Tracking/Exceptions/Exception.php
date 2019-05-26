<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Exceptions;

use ReliqArts\Logistiq\Exception as LogistiqException;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;

abstract class Exception extends LogistiqException
{
    /**
     * @var null|Trackable
     */
    protected $trackable;

    /**
     * @return null|Trackable
     */
    public function getTrackable(): ?Trackable
    {
        return $this->trackable;
    }
}
