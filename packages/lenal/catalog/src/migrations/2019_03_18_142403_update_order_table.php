<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('paysystem_id')->unsigned()->index()->nullable();
            $table->foreign('paysystem_id')->references('id')->on('paysystems')->onDelete('cascade');
            $table->boolean('is_payed')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('paysystem_id');
            $table->dropColumn('is_payed');
        });
    }
}
