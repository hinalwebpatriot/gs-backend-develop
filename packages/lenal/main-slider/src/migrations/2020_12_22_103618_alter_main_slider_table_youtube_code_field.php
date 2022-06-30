<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMainSliderTableYoutubeCodeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_slider_slides', function (Blueprint $table) {
            $table->string('youtube_code', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('main_slider_slides', function (Blueprint $table) {
            $table->dropColumn('youtube_code');
        });
    }
}
