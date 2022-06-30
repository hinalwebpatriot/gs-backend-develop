<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnToShowRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->json('tab_title');
            $table->json('desc_start');
            $table->json('desc_middle');
            $table->json('desc_end');
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
            $table->dropColumn('tab_title', 'desc_start', 'desc_middle', 'desc_end');
        });
    }
}
