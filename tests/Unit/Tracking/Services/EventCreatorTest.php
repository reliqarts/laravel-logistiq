<?php

/** @noinspection PhpParamsInspection */

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tests\Unit\Tracking\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use ReflectionClass;
use ReliqArts\Logistiq\Tests\TestCase;
use ReliqArts\Logistiq\Tracking\Contracts\EventCreator as EventCreatorCintract;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use ReliqArts\Logistiq\Tracking\Exceptions\TrackableEventCreationFailed;
use ReliqArts\Logistiq\Tracking\Services\EventCreator;
use stdClass;

/**
 * Class EventCreatorTest.
 *
 * @coversDefaultClass \ReliqArts\Logistiq\Tracking\Services\EventCreator
 *
 * @internal
 */
final class EventCreatorTest extends TestCase
{
    private const EVENT_CLASS_NAME = 'SomeEvent';
    /**
     * @var Application|ObjectProphecy
     */
    private $application;

    /**
     * @var ObjectProphecy|ReflectionClass
     */
    private $eventClass;

    /**
     * @var ObjectProphecy|Trackable
     */
    private $trackable;

    /**
     * @var EventCreatorCintract
     */
    private $subject;

    protected function setUp(): void
    {
        $this->application = $this->prophesize(Application::class);
        $this->trackable = $this->prophesize(Trackable::class);
        $this->eventClass = $this->prophesize(ReflectionClass::class);
        $this->subject = new EventCreator($this->application->reveal());
    }

    /**
     * @covers ::__construct
     * @covers ::createForTrackable
     *
     * @throws BindingResolutionException
     * @throws TrackableEventCreationFailed
     */
    public function testCreateForTrackable(): void
    {
        $this->application
            ->make(
                ReflectionClass::class,
                Argument::that(function (array $argument): bool {
                    return in_array(self::EVENT_CLASS_NAME, $argument, true);
                })
            )
            ->shouldBeCalledTimes(1)
            ->willReturn($this->eventClass);

        $this->eventClass
            ->newInstanceArgs([$this->trackable])
            ->shouldBeCalledTimes(1)
            ->willReturn(new stdClass());

        $this->subject->createForTrackable(
            self::EVENT_CLASS_NAME,
            $this->trackable->reveal()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::createForTrackable
     *
     * @throws TrackableEventCreationFailed
     * @throws BindingResolutionException
     */
    public function testCreateForTrackableWhenCreatingFails(): void
    {
        $this->application
            ->make(
                ReflectionClass::class,
                Argument::that(function (array $argument): bool {
                    return in_array(self::EVENT_CLASS_NAME, $argument, true);
                })
            )
            ->shouldBeCalledTimes(1)
            ->willThrow(BindingResolutionException::class);

        $this->eventClass
            ->newInstanceArgs(Argument::cetera())
            ->shouldNotBeCalled();

        $this->expectException(TrackableEventCreationFailed::class);
        $this->expectExceptionMessage(
            sprintf('Failed to create and inflate event: `%s`', self::EVENT_CLASS_NAME)
        );

        $this->subject->createForTrackable(
            self::EVENT_CLASS_NAME,
            $this->trackable->reveal()
        );
    }
}
