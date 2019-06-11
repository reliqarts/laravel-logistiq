<?php

declare(strict_types=1);

$prefix = 'logistiq_';

return [
    // debug mode?
    'debug' => false,

    // map statuses (by identifier) to different custom events
    'event_map' => [
        // e.g. '230c6c51-3b5b-4eea-9ef2-415e4d8fee00' => [ProductShipped::class, ProductMoved::class]
    ],

    // database config
    'tables' => [
        'tracking_updates' => sprintf('%stracking_updates', $prefix),
    ],
];
