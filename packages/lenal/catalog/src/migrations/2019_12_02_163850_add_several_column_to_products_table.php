<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeveralColumnToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('stone_size', 6, 2)->nullable();
            $table->string('setting_type', 40)->nullable();
            $table->string('side_setting_type', 40)->nullable();
            $table->integer('min_size_id')->nullable();
            $table->integer('max_size_id')->nullable();
            $table->string('carat_weight')->nullable();
            $table->string('average_ss_colour')->nullable();
            $table->string('average_ss_clarity')->nullable();
            $table->smallInteger('approx_stones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stone_size', 'setting_type', 'side_setting_type');
            $table->dropColumn('min_size_id', 'max_size_id', 'carat_weight');
            $table->dropColumn('average_ss_colour', 'average_ss_clarity', 'approx_stones');
        });
    }
}
