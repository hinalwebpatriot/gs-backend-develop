<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeddingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('thickness')->nullable();
            $table->string('diamond_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->text('description');
            $table->integer('thickness');
            $table->string('diamond_type');
        });
    }
}
