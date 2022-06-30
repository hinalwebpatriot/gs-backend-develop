<?php

use Faker\Generator as Faker;
use lenal\MainSlider\Models\MainSliderSlide;

$fakerRU = \Faker\Factory::create('ru_RU');
$fakerZH = \Faker\Factory::create('zh_TW');

$factory->define(MainSliderSlide::class, function (Faker $faker) use ($fakerRU, $fakerZH) {
    return [
        'title' => $faker->sentence,
        'image' => $faker->image(public_path('storage'), 1280,720, null, false),
        'bg_color' => $faker->hexColor,
        'slider_text->en' => $faker->realText(50, $indexSize = 5),
        'slider_text->ru' => $fakerRU->realText(50, $indexSize = 5),
        'slider_text->zh' => $fakerZH->realText(50, $indexSize = 5),
        'browse_button_title->en' => $faker->word,
        'browse_button_title->ru' => $fakerRU->word,
        'browse_button_title->zh' => $fakerZH->word,
        'browse_button_link->en'=> $faker->url,
        'browse_button_link->ru'=> $faker->url,
        'browse_button_link->zh'=> $faker->url,
        'detail_button_title->en' => $faker->word,
        'detail_button_title->ru' => $fakerRU->word,
        'detail_button_title->zh' => $fakerZH->word,
        'detail_button_link->en'=> $faker->url,
        'detail_button_link->ru'=> $faker->url,
        'detail_button_link->zh'=> $faker->url,
    ];
});
