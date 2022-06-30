<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShowRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->json('geo_title');
            $table->float('geo_position_lat', 10, 6);
            $table->float('geo_position_lng', 10, 6);
            $table->json('address');
            $table->string('image', 255)->nullable();
            $table->string('youtube_link', 255)->nullable();
            $table->string('phone', 40)->nullable();
            $table->json('phone_description')->nullable();
            $table->json('schedule')->nullable();
            $table->json('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('show_rooms');
    }
}
