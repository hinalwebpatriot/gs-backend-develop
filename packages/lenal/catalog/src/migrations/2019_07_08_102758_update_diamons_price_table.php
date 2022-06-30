<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDiamonsPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diamonds', function (Blueprint $table) {
            $table->unsignedDecimal('raw_price', 10, 2)
                ->change();
            $table->unsignedDecimal('margin_price', 10, 2)
                ->change();
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
            $table->unsignedDecimal('raw_price', 8, 2)->change();
            $table->unsignedDecimal('margin_price', 8, 2)->change();
        });
    }
}
