<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWeddingPropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->dropColumn('thickness');
        });
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->float('thickness', 8, 2)->nullable();
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
            $table->dropColumn('thickness');
        });
    }
}
