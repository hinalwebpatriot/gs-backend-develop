<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category');
            $table->json('label');
        });

        Schema::create('product_field_assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_field_id')->unsigned()->index();
            $table->string('product_type');
            $table->integer('product_id');
            $table->text('value')->nullable();

            $table->index(['product_type', 'product_id'], 'prod_type_idx');
            $table->foreign('product_field_id', 'product_field_fk')
                ->references('id')
                ->on('product_fields')
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
        Schema::table('product_field_assigns', function (Blueprint $table) {
            $table->dropForeign('product_field_fk');
            $table->drop();
        });

        Schema::dropIfExists('product_fields');
    }
}
