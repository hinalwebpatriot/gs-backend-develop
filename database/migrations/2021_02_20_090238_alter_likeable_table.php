<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLikeableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likeable_likes', function (Blueprint $table) {
            $table->dropUnique('likeable_likes_unique');
            $table->renameColumn('likable_id', 'likeable_id');
            $table->renameColumn('likable_type', 'likeable_type');
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likeable_likes_unique');
        });

        Schema::table('likeable_like_counters', function (Blueprint $table) {
            $table->dropIndex('likeable_counts');
            $table->renameColumn('likable_id', 'likeable_id');
            $table->renameColumn('likable_type', 'likeable_type');
            $table->unique(['likeable_id', 'likeable_type'], 'likeable_counts');
        });
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
