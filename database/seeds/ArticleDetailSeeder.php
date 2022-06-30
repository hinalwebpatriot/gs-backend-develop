<?php

use Illuminate\Database\Seeder;
use lenal\blog\Models\ArticleDetailBlock;
use Faker\Factory as Faker;

class ArticleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        factory(ArticleDetailBlock::class, 100)->create()
            ->each(function ($block) use ($faker) {
                if ($faker->numberBetween($min = -10, $max = 10) > 0 && !$block->video) {
                    $block
                        ->addMedia($faker->image(public_path('storage'), 1280, 800))
                        ->withCustomProperties(['language' => config('translatable.fallback_locale')])
                        ->toMediaCollection('image');
                }
        });
    }
}
