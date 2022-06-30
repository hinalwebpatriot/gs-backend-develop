<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMainSliderAddUndercoverVideoField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('main_slider_slides', function (Blueprint $table) {
            $table->string('undercover_video', 255)->nullable();
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
            $table->dropColumn('undercover_video');
        });
    }
}
