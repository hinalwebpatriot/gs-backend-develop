<?php

use Illuminate\Database\Seeder;
use lenal\blog\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BlogCategory::create([
            'title' => ['en' =>'Celebrity Engagement Rings'],
            'slug' => 'celebrity-engagement-rings',
            'priority' => 1
        ]);
        BlogCategory::create([
            'title' => ['en' =>'Engagement Rings'],
            'slug' => 'engagement-rings',
            'priority' => 2
        ]);
        BlogCategory::create([
            'title' => ['en' =>'Fine Jewerly'],
            'slug' => 'fine-jewerly',
            'priority' => 3
        ]);
        BlogCategory::create([
            'title' => ['en' =>'Trending Rings'],
            'slug' => 'trending-rings',
            'priority' => 4
        ]);
        BlogCategory::create([
            'title' => ['en' =>'News'],
            'slug' => 'news',
            'priority' => 5
        ]);
    }
}
