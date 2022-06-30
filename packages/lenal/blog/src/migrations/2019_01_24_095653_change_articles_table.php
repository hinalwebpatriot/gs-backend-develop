<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('preview_text')->nullable()->change();
            $table->dropColumn('content');
            //$table->renameColumn('preview_image', 'main_image');
            $table->dropColumn('preview_image');
            $table->dropColumn('tags');
            $table->string('slug')->unique();
            $table->integer('view_count');
            $table->integer('priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('preview_text')->nullable()->change();
            $table->longText('content');
            //$table->renameColumn('main_image', 'preview_image');
            $table->string('preview_image')->nullable();
            $table->string('tags')->nullable();
            $table->dropColumn('slug');
            $table->dropColumn('view_count');
            $table->dropColumn('priority');
        });
    }
}
