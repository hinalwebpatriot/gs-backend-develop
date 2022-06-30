<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page')->unique();
            $table->json('title')->nullable();
            $table->json('description')->nullable();
            $table->json('keywords')->nullable();
            $table->json('h1')->nullable();
            $table->json('seo_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seo_meta');
    }
}
