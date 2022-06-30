<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaysystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paysystems', function (Blueprint $table) {
            $table->string('type')->nullable()->default('other');
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paysystems', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropUnique(['slug']);
        });
    }
}
