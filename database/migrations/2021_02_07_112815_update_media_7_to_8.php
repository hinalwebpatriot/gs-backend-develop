<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class UpdateMedia7To8 extends Migration
{
    /**
     * Run the migrations.
     *
     * update `media` set uuid = (select UUID()), conversions_disk = disk
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('conversions_disk', 255)->nullable();
            $table->char('uuid', 36)->nullable()->unique();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('conversions_disk');
            $table->dropColumn('uuid');
        });
    }
}
