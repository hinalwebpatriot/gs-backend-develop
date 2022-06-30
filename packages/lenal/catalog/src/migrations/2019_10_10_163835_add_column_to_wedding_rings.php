<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToWeddingRings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->json('header')->nullable();
            $table->json('sub_header')->nullable();
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
            $table->dropColumn('header', 'sub_header');
        });
    }
}
