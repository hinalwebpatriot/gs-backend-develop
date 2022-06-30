<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShowRoomsTableAddMainGeoTitleField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_rooms', function (Blueprint $table) {
            $table->longText('main_geo_title')->after('id');
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
            $table->dropColumn('main_geo_title');
        });
    }
}
