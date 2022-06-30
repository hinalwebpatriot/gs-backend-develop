<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSeoBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_blocks', function (Blueprint $table) {
            $table->string('expert_name')->nullable();
            $table->string('brand')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_blocks', function (Blueprint $table) {
            $table->dropColumn('expert_name')->nullable();
            $table->dropColumn('brand')->nullable();
        });
    }
}
