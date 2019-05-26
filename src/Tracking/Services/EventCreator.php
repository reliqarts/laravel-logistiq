<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use ReflectionClass;
use ReliqArts\Logistiq\Tracking\Contracts\EventCreator as EventCreatorContract;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use ReliqArts\Logistiq\Tracking\Exceptions\TrackableEventCreationFailed;

final class EventCreator implements EventCreatorContract
{
    private const ARGUMENT = 'argument';

    /**
     * @var Application
     */
    private $application;

    /**
     * EventCreator constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @param string    $eventClassName
     * @param Trackable $trackable
     *
     * @throws TrackableEventCreationFailed
     *
     * @return mixed
     */
    public function createForTrackable(string $eventClassName, Trackable $trackable)
    {
        try {
            $eventClass = $this->application->make(ReflectionClass::class, [self::ARGUMENT => $eventClassName]);

            return $eventClass->newInstanceArgs([$trackable]);
        } catch (BindingResolutionException $exception) {
            throw TrackableEventCreationFailed::forEventClass($eventClassName, $trackable, $exception);
        }
    }
}
