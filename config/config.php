<?php

declare(strict_types=1);

$prefix = 'logistiq_';

return [
    // debug mode?
    'debug' => false,

    // map statuses (by identifier) to different custom events
    'event_map' => [
        // e.g. 'Status1' => ['ProductShipped', 'ProductMoved']
    ],

    // database config
    'tables' => [
        'tracking_updates' => sprintf('%stracking_updates', $prefix),
    ],
];
