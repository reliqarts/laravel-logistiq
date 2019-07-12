<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility\Eloquent;

use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;

class TrackingUpdate extends Model
{
    public const TABLE_KEY = 'tracking_updates';
    public const COLUMN_TRACKABLE_IDENTIFIER = 'trackable_identifier';
    public const COLUMN_TRACKABLE_TYPE = 'trackable_type';
    public const COLUMN_STATUS_IDENTIFIER = 'status_identifier';

    protected $guarded = [];

    /**
     * @return string
     */
    public function getTable()
    {
        return with(resolve(ConfigProvider::class))
            ->getTableNameByKey(self::TABLE_KEY);
    }
}
