<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRatioToDiamonds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diamonds', function (Blueprint $table) {
            $table->dropColumn('dimensions');
            $table->dropColumn('title');
            $table->float('size_ratio', 4, 2)->nullable();
            $table->float('length', 4, 2)->nullable();
            $table->float('width', 4, 2)->nullable();
            $table->float('height', 4, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diamonds', function (Blueprint $table) {
            $table->string('dimensions')->nullable();
            $table->json('title');
            $table->dropColumn('size_ratio');
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
