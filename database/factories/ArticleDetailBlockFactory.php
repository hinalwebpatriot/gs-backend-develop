<?php

use Faker\Generator as Faker;
use lenal\blog\Models\Article;
use lenal\blog\Models\ArticleDetailBlock;

$fakerRU = \Faker\Factory::create('ru_RU');
$fakerZH = \Faker\Factory::create('zh_TW');

$factory->define(ArticleDetailBlock::class, function (Faker $faker) use ($fakerRU, $fakerZH){
    $articles = Article::all();
    return [
        'article_id' => $faker->randomElement($articles),
        'title' => [
            'en' => $faker->realText(20, $indexSize = 5),
            'ru' => $fakerRU->realText(20, $indexSize = 5),
            'zh' => $fakerZH->realText(20, $indexSize = 5),
        ],
        'text' => [
            'en' => $faker->realText(300, $indexSize = 5),
            'ru' => $fakerRU->realText(300, $indexSize = 5),
            'zh' => $fakerZH->realText(300, $indexSize = 5),
        ],
        'video' => $faker->numberBetween($min = -10, $max = 10) > 0
            ? ['en' => 'https://www.youtube.com/watch?v=8yWkBukRCyw']
            : null
    ];
});
