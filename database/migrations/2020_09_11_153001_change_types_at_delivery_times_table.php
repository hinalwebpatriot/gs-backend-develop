<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypesAtDeliveryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_schema', function (Blueprint $table) {
            $table->integer('delivery_period')->nullable()->change()->default(0);
            $table->integer('delivery_period_wk')->nullable()->change()->default(0);
            $table->integer('metal_id')->nullable()->change()->default(0);
            $table->boolean('with_diamond')->nullable()->change()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
