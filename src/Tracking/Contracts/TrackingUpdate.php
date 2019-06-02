<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

interface TrackingUpdate
{
    /**
     * @param string $trackableIdentifier
     * @param string $trackableType
     * @param string $statusIdentifier
     *
     * @return mixed
     */
    public function log(string $trackableIdentifier, string $trackableType, string $statusIdentifier);
}
