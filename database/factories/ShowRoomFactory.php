<?php

use Faker\Generator as Faker;
use lenal\ShowRooms\Models\ShowRoom;
use lenal\ShowRooms\Models\ShowRoomCountry;

$fakerRU = \Faker\Factory::create('ru_RU');
$fakerZH = \Faker\Factory::create('zh_TW');

$factory->define(ShowRoom::class, function (Faker $faker)  use ($fakerRU, $fakerZH) {
    $countries = ShowRoomCountry::all();
    $address = $faker->address;
    $addressRU = $fakerRU->address;
    $addressZH = $fakerZH->address;
    return [
        'geo_title->en' => $address,
        'geo_title->ru' => $addressRU,
        'geo_title->zh' => $addressZH,
        'geo_position_lat' => $faker->latitude(),
        'geo_position_lng' => $faker->longitude(),
        'address->en' => $address,
        'address->ru' => $addressRU,
        'address->zh' => $addressZH,
        'image' => $faker->image(public_path('storage'), 500,300, 'city', false),
        'youtube_link->en' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
        'youtube_link->ru' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
        'youtube_link->zh' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
        'phone' => $faker->tollFreePhoneNumber,
        'phone_description->en' => $faker->realText(50, $indexSize = 5),
        'phone_description->ru' => $fakerRU->realText(50, $indexSize = 5),
        'phone_description->zh' => $fakerZH->realText(50, $indexSize = 5),
        'schedule->en' => $faker->time('D - D gA - gA'),
        'schedule->ru' => $fakerRU->time('D - D gA - gA'),
        'schedule->zh' => $fakerZH->time('D - D gA - gA'),
        'description->en' => $faker->realText(150, $indexSize = 5),
        'description->ru' => $fakerRU->realText(150, $indexSize = 5),
        'description->zh' => $fakerZH->realText(150, $indexSize = 5),
        'country_id' => $faker->randomElement($countries)
    ];
});
