<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_registration_text', function (Blueprint $table) {
            $table->integer('discount_percent')->default(0)->nullable();
            $table->integer('discount_value')->default(0)->nullable();
        });

        Schema::table('promocodes', function (Blueprint $table) {
            $table->integer('discount_value')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_registration_text', function (Blueprint $table) {
            $table->dropColumn('discount_percent', 'discount_value');
        });

        Schema::table('promocodes', function (Blueprint $table) {
            $table->dropColumn('discount_value');
        });
    }
}
