<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->integer('video_id')->nullable();
        });

        Schema::create('main_slider_slides', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('image', 255);
            $table->string('bg_color', 10)->nullable();
            $table->json('slider_text')->nullable();
            $table->json('browse_button_title')->nullable();
            $table->json('browse_button_link')->nullable();
            $table->json('detail_button_title')->nullable();
            $table->json('detail_button_link')->nullable();
        });

        Schema::create('main_slider_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->json('youtube_link');
        });

        Schema::create('main_slider_main_slider_slide', function (Blueprint $table) {
            $table->integer('main_slider_id')->unsigned()->index();
            $table->integer('main_slider_slide_id')->unsigned()->index();

            $table->foreign('main_slider_id')
                ->references('id')->on('main_sliders')
                ->onDelete('cascade');
            $table->foreign('main_slider_slide_id')
                ->references('id')->on('main_slider_slides')
                ->onDelete('cascade');

            $table->unique(['main_slider_id', 'main_slider_slide_id'], 'unique_main_sliders_main_slider_slides');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('main_sliders');
        Schema::dropIfExists('main_slider_slides');
        Schema::dropIfExists('main_slider_videos');
        Schema::dropIfExists('main_slider_main_slider_slide');

        Schema::enableForeignKeyConstraints();
    }
}
