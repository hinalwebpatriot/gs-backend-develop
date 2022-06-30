<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStoryToRingCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ring_collections', function (Blueprint $table) {
            $table->json('story_title')->nullable();
            $table->json('story_video')->nullable();
            $table->json('story_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ring_collections', function (Blueprint $table) {
            $table->dropColumn(['story_title', 'story_video', 'story_text']);
        });
    }
}
