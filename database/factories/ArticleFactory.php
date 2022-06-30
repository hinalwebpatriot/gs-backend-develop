<?php

use Faker\Generator as Faker;
use lenal\blog\Models\Article;
use lenal\blog\Models\BlogCategory;

$fakerRU = \Faker\Factory::create('ru_RU');
$fakerZH = \Faker\Factory::create('zh_TW');
$categories = BlogCategory::all();

$factory->define(Article::class, function (Faker $faker) use ($fakerRU, $fakerZH, $categories ){
    $date = $faker->dateTimeBetween('-1 year');
    $title = $faker->realText(50);
    return [
        'category_id' => $faker->randomElement($categories),
        'title' => [
            'en' => $title,
            'ru' => $fakerRU->realText(50, $indexSize = 5),
            'zh' => $fakerZH->realText(50, $indexSize = 5),
        ],
        'preview_text' => [
            'en' => $faker->realText(150, $indexSize = 5),
            'ru' => $fakerRU->realText(150, $indexSize = 5),
            'zh' => $fakerZH->realText(150, $indexSize = 5),
        ],
        'slug'=> str_slug($title, '-').$faker->bothify('-##??'),
        'view_count' => $faker->numberBetween($min = 0, $max = 10000) ,
        'priority' => $faker->numberBetween($min = 0, $max = 100),
        'created_at' => $date,
        'updated_at' => $date
    ];
});
