<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsTopColumnToEngagementRingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->boolean('is_top')->default(0)->index();
        });

        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->boolean('is_top')->default(0)->index();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_top')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->dropColumn('is_top');
        });

        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->dropColumn('is_top');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_top');
        });

    }
}
