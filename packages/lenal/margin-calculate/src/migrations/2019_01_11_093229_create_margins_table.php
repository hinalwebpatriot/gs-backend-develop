<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('margins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('manufacturer_id')->nullable();
            $table->boolean('is_round')->default(0);
            $table->unsignedInteger('clarity_id');
            $table->unsignedInteger('color_id');
            $table->float('carat_min', 5, 2);
            $table->float('carat_max', 5, 2);
            $table->float('margin', 5, 2);

            $table->foreign('manufacturer_id')
                ->references('id')->on('manufacturers')
                ->onDelete('cascade');

            $table->foreign('clarity_id')
                ->references('id')->on('clarities')
                ->onDelete('cascade');

            $table->foreign('color_id')
                ->references('id')->on('colors')
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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('margins');

        Schema::enableForeignKeyConstraints();
    }
}
