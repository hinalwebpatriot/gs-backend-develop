<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShowRoomsSetNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->longText('tab_title')->nullable()->change();
            $table->longText('desc_start')->nullable()->change();
            $table->longText('desc_middle')->nullable()->change();
            $table->longText('desc_end')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->longText('tab_title')->change();
            $table->longText('desc_start')->change();
            $table->longText('desc_middle')->change();
            $table->longText('desc_end')->change();
        });
    }
}
