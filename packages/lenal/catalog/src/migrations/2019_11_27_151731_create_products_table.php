<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 128)->unique();
            $table->string('sku', 128)->unique();
            $table->string('group_sku', 128)->index();
            $table->integer('category_id')->index();
            $table->integer('brand_id')->index();
            $table->integer('metal_id')->index();
            $table->char('gender', 6)->nullable();

            $table->json('item_name')->nullable();
            $table->json('description')->nullable();
            $table->json('header')->nullable();
            $table->json('sub_header')->nullable();

            $table->decimal('raw_price', 14, 2)->nullable();
            $table->decimal('cost_price', 14, 2)->nullable();
            $table->decimal('discount_price', 14, 2)->nullable();
            $table->decimal('inc_price', 14, 2)->nullable();

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
        Schema::dropIfExists('products');
    }
}
