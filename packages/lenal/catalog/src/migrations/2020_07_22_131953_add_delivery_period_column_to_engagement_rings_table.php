<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryPeriodColumnToEngagementRingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->integer('delivery_period')->default(0);
        });

        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->integer('delivery_period')->default(0);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('delivery_period')->default(0);
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
            $table->dropColumn('delivery_period');
        });

        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->dropColumn('delivery_period');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('delivery_period');
        });
    }
}
