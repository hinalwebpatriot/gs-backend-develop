<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->json('title');
            $table->json('description')->nullable();
            $table->string('slug', 40)->unique();
            $table->boolean('is_sale')->default(0);
            $table->float('discount', 4, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('offer_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->unsignedInteger('offer_id');

            $table->foreign('offer_id')
                ->references('id')
                ->on('offers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_relations');
        Schema::dropIfExists('offers');
    }
}
