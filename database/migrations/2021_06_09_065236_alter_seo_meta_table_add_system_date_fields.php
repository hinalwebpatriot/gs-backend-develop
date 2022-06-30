<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use lenal\seo\Models\Meta;

class AlterSeoMetaTableAddSystemDateFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            $table->string('sitemap_page_url')->nullable()->after('page');
            $table->timestamps();
        });
        Meta::query()->update(['created_at' => now(), 'updated_at' => now()]);
        DB::update('update seo_meta set sitemap_page_url = lower(replace(replace(replace(replace(replace(page, "engagement-rings-", "engagement-rings/"), "wedding-rings-", "wedding-rings/"), "blog-", "blog/"), "reviews-", "reviews/"), "diamonds-", "diamonds/"))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            $table->dropColumn('sitemap_page_url');
            $table->dropTimestamps();
        });
    }
}
