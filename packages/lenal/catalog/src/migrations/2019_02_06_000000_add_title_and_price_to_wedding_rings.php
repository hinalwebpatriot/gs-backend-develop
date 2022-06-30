<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleAndPriceToEngagementRings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->json('item_name');
            $table->unsignedDecimal('cost_price')->nullable();
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
            $table->dropColumn('item_name');
            $table->dropColumn('cost_price');
        });
    }
}
