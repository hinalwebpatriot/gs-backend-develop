<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dynamic_page_id')->unsigned()->index()->  nullable();
            $table->foreign('dynamic_page_id')->references('id')->on('dynamic_pages')->onDelete('cascade');
            $table->string('block_type')->nullable();
            $table->json('title')->nullable();
            $table->json('text')->nullable();
            $table->string('image')->nullable();
            $table->json('file')->nullable();
            $table->json('video_link')->nullable();
            $table->json('link')->nullable();
        });

        Schema::create('static_blocks_diamonds', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('static_block_id')->unsigned()->index();
            $table->foreign('static_block_id')->references('id')->on('static_blocks')->onDelete('cascade');
            $table->integer('diamond_id')->unsigned()->index();
            $table->foreign('diamond_id')->references('id')->on('diamonds');
        });
        Schema::create('static_blocks_engagement_rings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('static_block_id')->unsigned()->index();
            $table->foreign('static_block_id')->references('id')->on('static_blocks')->onDelete('cascade');
            $table->integer('engagement_ring_id')->unsigned()->index();
            $table->foreign('engagement_ring_id')->references('id')->on('engagement_rings');
        });
        Schema::create('static_blocks_wedding_rings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('static_block_id')->unsigned()->index();
            $table->foreign('static_block_id')->references('id')->on('static_blocks')->onDelete('cascade');
            $table->integer('wedding_ring_id')->unsigned()->index();
            $table->foreign('wedding_ring_id')->references('id')->on('wedding_rings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('static_blocks');
    }
}
