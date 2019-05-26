<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Contracts;

interface EventDispatcher
{
    /**
     * Wrapper around Illuminate dispatcher.
     *
     * @param mixed ...$args
     *
     * @return null|array
     *
     * @see \Illuminate\Contracts\Events\Dispatcher for details on arguments
     */
    public function dispatch(...$args): ?array;
}
