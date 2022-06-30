<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleDetailBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_detail_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->json('title')->nullable();
            $table->json('text');
            $table->json('video')->nullable();
            $table->integer('article_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_detail_blocks');
    }
}
