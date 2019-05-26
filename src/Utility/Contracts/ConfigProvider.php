<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Contracts;

use ReliqArts\Logistiq\Utility\Exceptions\TableNameNotFound;

interface ConfigProvider
{
    /**
     * @param string $statusIdentifier
     *
     * @return array
     */
    public function getEventsForStatus(string $statusIdentifier): array;

    /**
     * @param string $key
     *
     * @throws TableNameNotFound
     *
     * @return string
     */
    public function getTableNameByKey(string $key): string;
}
