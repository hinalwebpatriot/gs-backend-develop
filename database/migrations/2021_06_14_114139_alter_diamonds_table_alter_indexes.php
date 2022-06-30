<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDiamondsTableAlterIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diamonds', function (Blueprint $table) {
            $table->dropIndex('diamonds_shape_id_index');
            $table->dropIndex('diamonds_color_id_index');
            $table->dropIndex('diamonds_cut_id_index');
            $table->dropIndex('diamonds_polish_id_index');
            $table->dropIndex('diamonds_symmetry_id_index');
            $table->dropIndex('diamonds_fluorescence_id_index');
            $table->dropIndex('diamonds_clarity_id_index');
            $table->dropIndex('diamonds_culet_id_index');
            $table->dropIndex('diamonds_raw_price_index');
            $table->dropIndex('diamonds_manufacturer_id_index');
            $table->dropIndex('diamond_margin_price_index');
            $table->dropIndex('is_offline_idx');

            $table->index(['enabled', 'is_offline', 'cut_id', 'carat', 'color_id', 'clarity_id', 'raw_price', 'margin_price'], 'idx-for_filters_feed');
            $table->index(['enabled', 'is_offline', 'cut_id', 'carat', 'color_id', 'clarity_id', 'shape_id', 'raw_price', 'margin_price'], 'idx-for_filters_shape');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diamonds', function (Blueprint $table) {
            $table->dropIndex('idx-for_filters_shape');
            $table->dropIndex('idx-for_filters_feed');
        });
    }
}
