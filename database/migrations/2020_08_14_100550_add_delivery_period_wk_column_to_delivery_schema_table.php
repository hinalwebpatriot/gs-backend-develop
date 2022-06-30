<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryPeriodWkColumnToDeliverySchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_schema', function (Blueprint $table) {
            $table->integer('delivery_period_wk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_schema', function (Blueprint $table) {
            $table->dropColumn('delivery_period_wk');
        });
    }
}
