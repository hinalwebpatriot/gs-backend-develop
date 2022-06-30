<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 32)->unique();
            $table->string('kind', 16);
            $table->float('discount')->default(0);
            $table->dateTime('validity_date')->nullable();
            $table->string('personal_email', 64)->nullable();
            $table->string('confirm_code', 16)->nullable();
            $table->integer('max_times')->default(1);
            $table->integer('used_times')->default(0);
            $table->string('category', 255)->nullable();
            $table->string('products_sku', 512)->nullable();
            $table->string('collections', 512)->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
