<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ReliqArts\Logistiq\Tracking\Models\TrackingUpdate;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider;
use ReliqArts\Logistiq\Utility\Exceptions\TableNameNotFound;

class CreateLogistiqTrackingUpdatesTable extends Migration
{
    /**
     * @var string
     */
    private $table;

    /**
     * CreateLogistiqTrackingUpdatesTable constructor.
     *
     * @throws TableNameNotFound
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
            $table->string('trackable_identifier');
            $table->string('trackable_type');
            $table->string('status_identifier');
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
