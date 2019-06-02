<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ReliqArts\Logistiq\Tracking\Models\TrackingUpdate;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;

class CreateLogistiqTrackingUpdatesTable extends Migration
{
    /**
     * @var string
     */
    private $table;

    /**
     * CreateLogistiqTrackingUpdatesTable constructor.
     */
    public function __construct()
    {
        /**
         * @var ConfigProvider
         */
        $configProvider = resolve(ConfigProvider::class);

        $this->table = $configProvider->getTableNameByKey(TrackingUpdate::TABLE_KEY);
    }

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string(TrackingUpdate::COLUMN_TRACKABLE_IDENTIFIER);
            $table->string(TrackingUpdate::COLUMN_TRACKABLE_TYPE);
            $table->string(TrackingUpdate::COLUMN_STATUS_IDENTIFIER);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
