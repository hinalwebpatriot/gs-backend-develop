<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasNewRendersField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('engagement_rings', function (Blueprint $table) {
            $table->boolean('has_new_renders')->default(false);
        });
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->boolean('has_new_renders')->default(false);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_new_renders')->default(false);
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
            $table->dropColumn('has_new_renders');
        });
        Schema::table('wedding_rings', function (Blueprint $table) {
            $table->dropColumn('has_new_renders');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_new_renders');
        });
    }
}
