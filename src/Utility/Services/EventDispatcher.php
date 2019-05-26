<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Services;

use Illuminate\Contracts\Events\Dispatcher;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher as EventDispatcherContract;

final class EventDispatcher implements EventDispatcherContract
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * EventDispatcher constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Wrapper around Illuminate dispatcher.
     *
     * @param mixed ...$args
     *
     * @return null|array
     *
     * @see \Illuminate\Contracts\Events\Dispatcher for details on arguments
     */
    public function dispatch(...$args): ?array
    {
        return $this->dispatcher->dispatch(...$args);
    }
}
