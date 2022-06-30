<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomSortFieldForRingsAndProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->integer('custom_sort')->default(0);
        });
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->integer('custom_sort')->default(0);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->integer('custom_sort')->default(0);
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
            $table->dropColumn('custom_sort');
        });
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->dropColumn('custom_sort');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('custom_sort');
        });
    }
}
