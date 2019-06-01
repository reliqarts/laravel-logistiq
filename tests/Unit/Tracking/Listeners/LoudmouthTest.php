<?php
/**
 * @noinspection PhpUndefinedMethodInspection
 * PhpVoidFunctionResultUsedInspection
 * PhpStrictTypeCheckingInspection
 * PhpParamsInspection
 */

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tests\Unit\Tracking\Listeners;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Logistiq\Tests\TestCase;
use ReliqArts\Logistiq\Tracking\Contracts\EventCreator;
use ReliqArts\Logistiq\Tracking\Contracts\Status;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use ReliqArts\Logistiq\Tracking\Events\StatusChanged;
use ReliqArts\Logistiq\Tracking\Exceptions\TrackableEventCreationFailed;
use ReliqArts\Logistiq\Tracking\Listeners\Loudmouth;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher;
use ReliqArts\Logistiq\Utility\Contracts\Logger;

/**
 * Class LoudmouthTest.
 *
 * @coversDefaultClass \ReliqArts\Logistiq\Tracking\Listeners\Loudmouth
 *
 * @internal
 */
final class LoudmouthTest extends TestCase
{
    private const STATUS_IDENTIFIER = '7fo4-oi43-23ed-po10';

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ConfigProvider|ObjectProphecy
     */
    private $configProvider;

    /**
     * @var EventCreator|ObjectProphecy
     */
    private $eventCreator;

    /**
     * @var EventDispatcher|ObjectProphecy
     */
    private $eventDispatcher;

    /**
     * @var Loudmouth
     */
    private $subject;

    protected function setUp(): void
    {
        $this->eventCreator = $this->prophesize(EventCreator::class);
        $this->logger = $this->prophesize(Logger::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);
        $this->configProvider = $this->prophesize(ConfigProvider::class);

        $this->subject = new Loudmouth(
            $this->logger->reveal(),
            $this->configProvider->reveal(),
            $this->eventCreator->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    /**
     * @dataProvider statusEventNameProvider
     * @covers ::__construct
     * @covers ::dispatchStatusEvents
     * @covers ::handle
     *
     * @param string[] $statusEvents
     *
     * @throws TrackableEventCreationFailed
     */
    public function testHandle(string ...$statusEvents): void
    {
        $statusChangedEvent = $this->getStatusChangedEvent();
        $statusEventsCount = count($statusEvents);

        $this->configProvider
            ->getEventsForStatus(self::STATUS_IDENTIFIER)
            ->shouldBeCalledTimes(1)
            ->willReturn($statusEvents);

        $this->eventCreator
            ->createForTrackable(
                Argument::that(function (string $argument) use ($statusEvents): bool {
                    return in_array($argument, $statusEvents, true);
                }),
                $statusChangedEvent->getTrackable()
            )
            ->shouldBeCalledTimes($statusEventsCount);

        $this->eventDispatcher
            ->dispatch(Argument::any())
            ->shouldBeCalledTimes($statusEventsCount);

        $this->logger
            ->error(Argument::cetera())
            ->shouldNotBeCalled();

        $this->subject->handle($statusChangedEvent);
    }

    /**
     * @covers ::__construct
     * @covers ::dispatchStatusEvents
     * @covers ::handle
     *
     * @throws TrackableEventCreationFailed
     */
    public function testWhenEventResolutionFails(): void
    {
        $statusChangedEvent = $this->getStatusChangedEvent();
        $badEventClassName = 'MissingEvent';
        $goodEventClassName = 'MyEvent';
        $statusEvents = [$badEventClassName, $goodEventClassName];
        $trackable = $statusChangedEvent->getTrackable();

        $this->configProvider
            ->getEventsForStatus(self::STATUS_IDENTIFIER)
            ->shouldBeCalledTimes(1)
            ->willReturn($statusEvents);

        $this->eventCreator
            ->createForTrackable($goodEventClassName, $trackable)
            ->shouldBeCalledTimes(1)
            ->willReturn($goodEventClassName);
        $this->eventCreator
            ->createForTrackable($badEventClassName, $trackable)
            ->shouldBeCalledTimes(1)
            ->willThrow(TrackableEventCreationFailed::class);

        $this->eventDispatcher
            ->dispatch($goodEventClassName)
            ->shouldBeCalledTimes(1);
        $this->eventDispatcher
            ->dispatch($badEventClassName)
            ->shouldNotBeCalled();

        $this->logger
            ->error(Argument::containingString($goodEventClassName), Argument::type('array'))
            ->shouldNotBeCalled();
        $this->logger
            ->error(Argument::containingString($badEventClassName), Argument::type('array'))
            ->shouldBeCalledTimes(1);

        $this->subject->handle($statusChangedEvent);
    }

    /**
     * @return array
     */
    public function statusEventNameProvider(): array
    {
        return [
            [
                'Namespace\A\B\Event1',
            ],
            [
                'Foo',
                'Bar',
                'Cat',
                'Dog',
                'Hello_World',
                'LifeOrPie',
            ],
        ];
    }

    /**
     * @return object|StatusChanged
     */
    private function getStatusChangedEvent(): StatusChanged
    {
        $status = $this->prophesize(Status::class);
        $event = $this->prophesize(StatusChanged::class);
        $trackable = $this->prophesize(Trackable::class);

        $status
            ->getIdentifier()
            ->willReturn(self::STATUS_IDENTIFIER);

        $event
            ->getStatus()
            ->willReturn($status);
        $event
            ->getTrackable()
            ->willReturn($trackable);

        return $event->reveal();
    }
}
