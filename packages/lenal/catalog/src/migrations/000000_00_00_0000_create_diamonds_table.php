<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiamondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diamonds', function (Blueprint $table) {
            $table->increments('id');
            $table->json('title');
            $table->string('slug')->unique()->index();
            $table->string('sku')->unique()->index();
            $table->unsignedInteger('shape_id')->index()->nullable();
            $table->unsignedInteger('color_id')->index()->nullable();
            $table->unsignedInteger('cut_id')->index()->nullable(); // good, very good, ideal, true hearts
            $table->unsignedInteger('polish_id')->index()->nullable(); // good, very good, excellent
            $table->unsignedInteger('symmetry_id')->index()->nullable(); // good, very good, excellent
            $table->unsignedInteger('fluorescence_id')->index()->nullable(); // none, faint, medium, strong, very strong
            $table->unsignedInteger('clarity_id')->index()->nullable(); // fl, lf, ws1, ws2 etc
            $table->unsignedInteger('culet_id')->index()->nullable();
            $table->unsignedDecimal('raw_price', 8, 2)->index()->nullable();
            $table->float('carat', 4, 2)->nullable();
            $table->float('depth', 4, 2)->nullable();
            $table->float('table', 4, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->string('manufacturer')->nullable();
            /*
            $table->float('carat'); // from 0.1 to 8

            $table->unsignedInteger('depth'); // from 0 to 80
            $table->unsignedInteger('table'); // from 0 to 100 percent
            $table->string('lab'); // GIA etc


            $table->float('length_width_ratio'); // from 0.75 to 5
            $table->string('certificate');
            $table->timestamps();
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diamonds');
    }
}

