<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

interface Identifiable
{
    /**
     * Get value to be used throughout Logistiq to identify the current entity.
     *
     * Suggestions: ID, UUID
     *
     * @return mixed
     */
    public function getIdentifier();
}
