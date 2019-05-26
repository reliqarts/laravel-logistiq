<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

use ReliqArts\Logistiq\Utility\Eloquent\Model;

interface TrackingUpdate
{
    /**
     * @param array $attributes
     *
     * @return Model|self
     */
    public function create(array $attributes = []);
}
