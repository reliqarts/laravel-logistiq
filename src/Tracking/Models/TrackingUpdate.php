<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tracking\Models;

use ReliqArts\Logistiq\Tracking\Contracts\TrackingUpdate as TrackingUpdateContract;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;
use ReliqArts\Logistiq\Utility\Eloquent\Model;

final class TrackingUpdate extends Model implements TrackingUpdateContract
{
    public const TABLE_KEY = 'tracking_updates';

    protected $fillable = ['trackable_identifier', 'trackable_type', 'status_identifier'];

    /**
     * @return string
     */
    public function getTable()
    {
        return with(resolve(ConfigProvider::class))
            ->getTableNameByKey(self::TABLE_KEY);
    }

    /**
     * @param array $attributes
     *
     * @return Model|TrackingUpdateContract
     */
    public function create(array $attributes = [])
    {
        return parent::create($attributes);
    }
}
