<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('is_active')->default(0);
            $table->string('title')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->text('text')->nullable();
            $table->integer('rate')->nullable();
            //$table->morphs('product');
            $table->integer('product_id') -> unsigned() -> index()->nullable();
            $table->string('product_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
