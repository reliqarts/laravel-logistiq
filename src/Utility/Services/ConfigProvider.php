<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Services;

use ReliqArts\Contracts\ConfigProvider as ConfigAccessor;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider as ConfigProviderContract;
use ReliqArts\Logistiq\Utility\Exceptions\TableNameNotFound;

final class ConfigProvider implements ConfigProviderContract
{
    private const EVENT_MAP_KEY = 'event_map';
    private const TABLES_KEY = 'tables';

    /**
     * @var ConfigAccessor
     */
    private $configAccessor;

    /**
     * ConfigProvider constructor.
     *
     * @param ConfigAccessor $configAccessor
     */
    public function __construct(ConfigAccessor $configAccessor)
    {
        $this->configAccessor = $configAccessor;
    }

    /**
     * @param string $statusIdentifier
     *
     * @return array
     */
    public function getEventsForStatus(string $statusIdentifier): array
    {
        $eventKey = sprintf('%s.%s', self::EVENT_MAP_KEY, $statusIdentifier);
        $eventClasses = $this->configAccessor->get($eventKey, []);

        if (empty($eventClasses) || !is_array($eventClasses)) {
            return [];
        }

        return array_map('trim', $eventClasses);
    }

    /**
     * @param string $key
     *
     * @throws TableNameNotFound
     *
     * @return string
     */
    public function getTableNameByKey(string $key): string
    {
        $tableKey = sprintf('%s.%s', self::TABLES_KEY, $key);
        $tableName = $this->configAccessor->get($tableKey);

        if (empty($tableName)) {
            throw TableNameNotFound::forKey($key);
        }

        return $tableName;
    }
}
