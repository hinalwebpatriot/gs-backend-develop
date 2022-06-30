<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEngagementRingsTableIsActiveField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
           $table->boolean('is_active')->default(true);
        });
        Schema::table('wedding_rings', function (Blueprint $table) {
           $table->boolean('is_active')->default(true);
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
            $table->dropColumn('is_active');
        });
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
