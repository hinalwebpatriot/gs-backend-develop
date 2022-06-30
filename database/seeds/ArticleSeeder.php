<?php

use Illuminate\Database\Seeder;
use lenal\blog\Models\Article;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        factory(Article::class, 20)->create()
            ->each(function ($article) use ($faker) {
                $article
                    ->addMedia($faker->image(public_path('storage'), 1280, 800))
                    ->toMediaCollection('main_image');
            });
    }
}
