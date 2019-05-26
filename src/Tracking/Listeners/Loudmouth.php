<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Listeners;

use ReliqArts\Logistiq\Tracking\Contracts\EventCreator;
use ReliqArts\Logistiq\Tracking\Events\StatusChanged;
use ReliqArts\Logistiq\Tracking\Exceptions\TrackableEventCreationFailed;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher;
use ReliqArts\Logistiq\Utility\Contracts\Logger;

final class Loudmouth
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var EventCreator
     */
    private $eventCreator;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Resonator constructor.
     *
     * @param Logger          $logger
     * @param ConfigProvider  $configProvider
     * @param EventCreator    $eventCreator
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        Logger $logger,
        ConfigProvider $configProvider,
        EventCreator $eventCreator,
        EventDispatcher $eventDispatcher
    ) {
        $this->logger = $logger;
        $this->configProvider = $configProvider;
        $this->eventCreator = $eventCreator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param StatusChanged $event
     */
    public function handle(StatusChanged $event): void
    {
        $this->dispatchStatusEvents($event);
    }

    /**
     * Dispatch configured status-dependent events.
     *
     * @param StatusChanged $event
     */
    private function dispatchStatusEvents(StatusChanged $event): void
    {
        $status = $event->getStatus();
        $eventNamesForStatus = $this->configProvider->getEventsForStatus((string)$status->getIdentifier());

        foreach ($eventNamesForStatus as $nextEventClassName) {
            try {
                $nextEvent = $this->eventCreator->createForTrackable($nextEventClassName, $event->getTrackable());

                $this->eventDispatcher->dispatch($nextEvent);
            } catch (TrackableEventCreationFailed $exception) {
                $this->logger->error(
                    sprintf('Failed to dispatch event: `%s`. %s', $nextEventClassName, $exception->getMessage()),
                    $exception->getTrace()
                );
            }
        }
    }
}
