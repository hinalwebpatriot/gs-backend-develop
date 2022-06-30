<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->json('title');
            $table->string('slug');
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
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('title');
            $table->dropColumn('slug');
            $table->dropColumn('priority');
        });
    }
}
