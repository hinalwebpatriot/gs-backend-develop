<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollapsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collapses', function (Blueprint $table) {
            $table->id();
            $table->morphs('collapsable', 'idx-collapsable-morphs');
            $table->string('title');
            $table->text('content');
            $table->unsignedSmallInteger('position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collapses');
    }
}
