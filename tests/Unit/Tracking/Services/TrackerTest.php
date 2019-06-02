<?php

/** @noinspection PhpUndefinedMethodInspection PhpParamsInspection PhpStrictTypeCheckingInspection */

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tests\Unit\Tracking\Services;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Logistiq\Tests\TestCase;
use ReliqArts\Logistiq\Tracking\Contracts\Status;
use ReliqArts\Logistiq\Tracking\Contracts\Trackable;
use ReliqArts\Logistiq\Tracking\Contracts\Tracker as TrackerContract;
use ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate;
use ReliqArts\Logistiq\Tracking\Events\StatusChanged;
use ReliqArts\Logistiq\Tracking\Exceptions\StatusChangeFailed;
use ReliqArts\Logistiq\Tracking\Services\Tracker;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher;

/**
 * Class TrackerTest.
 *
 * @coversDefaultClass \ReliqArts\Logistiq\Tracking\Services\Tracker
 *
 * @internal
 */
final class TrackerTest extends TestCase
{
    /**
     * @var EventDispatcher|ObjectProphecy
     */
    private $eventDispatcher;

    /**
     * @var ObjectProphecy|Trackable
     */
    private $trackable;

    /**
     * @var ObjectProphecy|Status
     */
    private $status;

    /**
     * @var ObjectProphecy|Status
     */
    private $oldStatus;

    /**
     * @var ObjectProphecy|TrackingUpdate
     */
    private $trackingUpdate;

    /**
     * @var TrackerContract
     */
    private $subject;

    protected function setUp(): void
    {
        $this->trackable = $this->prophesize(Trackable::class);
        $this->oldStatus = $this->prophesize(Status::class);
        $this->status = $this->prophesize(Status::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcher::class);
        $this->trackingUpdate = $this->prophesize(TrackingUpdate::class);

        $this->subject = new Tracker($this->eventDispatcher->reveal(), $this->trackingUpdate->reveal());
    }

    /**
     * @covers ::__construct
     * @covers ::registerStatusChange
     * @covers ::setTrackableStatus
     *
     * @throws StatusChangeFailed
     */
    public function testSetTrackableStatus(): void
    {
        $this->trackable
            ->getStatus()
            ->shouldBeCalledTimes(1)
            ->willReturn($this->oldStatus);
        $this->trackable
            ->setStatus($this->status)
            ->shouldBeCalledTimes(1)
            ->willReturn(true);
        $this->trackable
            ->getIdentifier()
            ->shouldBeCalledTimes(1)
            ->willReturn('5dek-23fs-83mn-24gf');

        $this->eventDispatcher
            ->dispatch(new StatusChanged(
                $this->trackable->reveal(),
                $this->oldStatus->reveal(),
                $this->status->reveal()
            ))
            ->shouldBeCalledTimes(1);

        $this->trackingUpdate
            ->log(Argument::cetera())
            ->shouldBeCalledTimes(1);

        $this->subject->setTrackableStatus(
            $this->trackable->reveal(),
            $this->status->reveal()
        );
    }

    /**
     * @covers ::__construct
     * @covers ::setTrackableStatus
     * @covers \ReliqArts\Logistiq\Tracking\Exceptions\StatusChangeFailed
     *
     * @throws StatusChangeFailed
     */
    public function testSetTrackableStatusThrowsExceptionOnFailure(): void
    {
        $this->trackable
            ->getStatus()
            ->shouldBeCalledTimes(1)
            ->willReturn($this->oldStatus);
        $this->trackable
            ->setStatus($this->status)
            ->shouldBeCalledTimes(1)
            ->willReturn(false);
        $this->trackable
            ->getIdentifier()
            ->shouldBeCalledTimes(1)
            ->willReturn(43452);

        $this->eventDispatcher
            ->dispatch(Argument::any())
            ->shouldNotBeCalled();

        $this->trackingUpdate
            ->log(Argument::cetera())
            ->shouldNotBeCalled();

        $this->status
            ->getName()
            ->shouldBeCalledTimes(1)
            ->willReturn('Foo');

        $this->expectException(StatusChangeFailed::class);
        $this->expectExceptionMessage('Status change failed');

        $this->subject->setTrackableStatus(
            $this->trackable->reveal(),
            $this->status->reveal()
        );
    }
}
