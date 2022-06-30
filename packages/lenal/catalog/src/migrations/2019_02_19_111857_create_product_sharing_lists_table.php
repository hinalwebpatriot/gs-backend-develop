<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSharingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sharing_lists', function (Blueprint $table) {
            $table->increments('id');

        });
        Schema::create('sharing_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('list_id')->unsigned()->index()->nullable();
            $table->foreign('list_id')->references('id')->on('product_sharing_lists')->onDelete('cascade');
            $table->morphs('product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sharing_products');
        Schema::dropIfExists('product_sharing_lists');
    }
}
