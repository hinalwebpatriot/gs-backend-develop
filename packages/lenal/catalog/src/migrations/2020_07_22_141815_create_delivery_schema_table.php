<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverySchemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_schema', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_slug', 32);
            $table->integer('metal_id')->default(0);
            $table->boolean('with_diamond')->default(0);
            $table->integer('delivery_period')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_schema');
    }
}
