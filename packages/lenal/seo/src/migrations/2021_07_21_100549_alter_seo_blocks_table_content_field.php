<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSeoBlocksTableContentField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blocks = \lenal\seo\Models\SEOBlock::all();
        foreach ($blocks as $block) {
            $title = json_decode($block->title, true);
            $description = json_decode($block->description, true);
            $block->title = $title['en'];
            $block->description = $description['en'];
            $block->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
