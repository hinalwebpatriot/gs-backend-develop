<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_diamond_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('manufacturer_id');
            $table->integer('ymd');

            $table->integer('create_job')->default(0);
            $table->integer('update_job')->default(0);
            $table->integer('delete_job')->default(0);

            $table->integer('create_request')->default(0);
            $table->integer('update_request')->default(0);
            $table->integer('delete_request')->default(0);

            $table->integer('created')->default(0);
            $table->integer('updated')->default(0);
            $table->integer('deleted')->default(0);

            $table->integer('create_err')->default(0);
            $table->integer('update_err')->default(0);
            $table->integer('delete_err')->default(0);

            $table->timestamps();

            $table->unique(['manufacturer_id', 'ymd'], 'manufacturer_ymd_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('import_diamond_statistics', function (Blueprint $table) {
            $table->dropUnique('manufacturer_ymd_idx');
        });

        Schema::dropIfExists('import_diamond_statistics');
    }
}
