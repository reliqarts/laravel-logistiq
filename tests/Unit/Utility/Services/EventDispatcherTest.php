<?php

/** @noinspection PhpUndefinedMethodInspection PhpParamsInspection */

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tests\Unit\Utility\Services;

use Illuminate\Contracts\Events\Dispatcher as IlluminateDispatcher;
use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Logistiq\Tests\TestCase;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher as EventDispatcherContract;
use ReliqArts\Logistiq\Utility\Services\EventDispatcher;

/**
 * Class EventDispatcherTest.
 *
 * @coversDefaultClass \ReliqArts\Logistiq\Utility\Services\EventDispatcher
 *
 * @internal
 */
final class EventDispatcherTest extends TestCase
{
    /**
     * @var IlluminateDispatcher|ObjectProphecy
     */
    private $illuminateDispatcher;

    /**
     * @var EventDispatcherContract
     */
    private $subject;

    protected function setUp(): void
    {
        $this->illuminateDispatcher = $this->prophesize(IlluminateDispatcher::class);
        $this->subject = new EventDispatcher($this->illuminateDispatcher->reveal());
    }

    /**
     * @covers ::__construct
     * @covers ::dispatch
     */
    public function testDispatch(): void
    {
        $args = ['param-1', 'param-2'];

        $this->illuminateDispatcher
            ->dispatch(...$args)
            ->shouldBeCalledTimes(1);

        $this->subject->dispatch(...$args);
    }
}
