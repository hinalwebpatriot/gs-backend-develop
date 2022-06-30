<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeliverySchemasTableStyleField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_schema', function (Blueprint $table) {
            $table->unsignedInteger('ring_style_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_schema', function (Blueprint $table) {
            $table->dropColumn('ring_style_id');
        });
    }
}
