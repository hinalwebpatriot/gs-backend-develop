<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAveragePropsToEr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->string('average_ss_colour')->nullable();
            $table->string('average_ss_clarity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->dropColumn('average_ss_colour');
            $table->dropColumn('average_ss_clarity');
        });
    }
}
