<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEngagementRingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('engagement_rings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique()->index();
            $table->string('sku')->unique()->index();
            $table->string('group_sku')->index();
            $table->unsignedDecimal('band_width', 2, 1)->nullable();
            $table->unsignedDecimal('raw_price', 8, 2)->index();
            $table->unsignedInteger('ring_collection_id')->index();
            $table->unsignedInteger('ring_style_id')->index()->nullable();
            $table->unsignedInteger('stone_shape_id')->index();
            $table->unsignedDecimal('stone_size', 3, 2)->index();
            $table->string('setting_type', 40)->index();
            $table->string('side_setting_type', 40)->nullable();
            $table->unsignedInteger('min_ring_size_id')->index();
            $table->unsignedInteger('max_ring_size_id')->index();
            $table->unsignedInteger('metal_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('engagement_rings');
    }
}

