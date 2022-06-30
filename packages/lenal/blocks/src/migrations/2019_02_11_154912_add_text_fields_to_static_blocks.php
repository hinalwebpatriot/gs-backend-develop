<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTextFieldsToStaticBlocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_blocks', function (Blueprint $table) {
            $table->json('link_text')->nullable();
            $table->json('subtitle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('static_blocks', function (Blueprint $table) {
            $table->dropColumn('link_text');
            $table->dropColumn('subtitle');
        });
    }
}
