<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Exceptions;

use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use Throwable;

final class TrackableEventCreationFailed extends Exception
{
    protected const CODE = 5100;

    /**
     * @var string
     */
    private $eventClassName;

    /**
     * @param string         $eventClassName
     * @param Trackable      $trackable
     * @param null|Throwable $previous
     *
     * @return self
     */
    public static function forEventClass(
        string $eventClassName,
        Trackable $trackable,
        Throwable $previous = null
    ): self {
        $message = sprintf(
            'Failed to create and inflate event: `%s`,  with trackable: `%s`.',
            $eventClassName,
            $trackable->getIdentifier()
        );

        $instance = new self($message, static::CODE, $previous);
        $instance->eventClassName = $eventClassName;

        return $instance;
    }
}
