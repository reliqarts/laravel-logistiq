<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Contracts;

interface Status extends Identifiable
{
    /**
     * @return string
     */
    public function getName(): string;
}
