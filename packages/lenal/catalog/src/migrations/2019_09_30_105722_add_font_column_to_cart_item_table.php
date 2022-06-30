<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFontColumnToCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('engraving_font', 64)->nullable();
        });

        Schema::table('complete_rings', function (Blueprint $table) {
            $table->string('engraving_font', 64)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('engraving_font');
        });

        Schema::table('complete_rings', function (Blueprint $table) {
            $table->dropColumn('engraving_font');
        });
    }
}
