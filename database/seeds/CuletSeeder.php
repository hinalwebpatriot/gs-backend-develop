<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Culet;

class CuletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Culet::create([
            'title->en' => 'Pointed',
            'title->ru' => 'Заточенная',
            'title->zh' => '优',
            'slug' => 'pointed'
        ]);
        Culet::create([
            'title->en' => 'Medium',
            'title->ru' => 'Небольшая грань',
            'title->zh' => '非常好',
            'slug' => 'medium'
        ]);
        Culet::create([
            'title->en' => 'Faceted',
            'title->ru' => 'Грань',
            'title->zh' => '好的',
            'slug' => 'faceted'
        ]);
        Culet::create([
            'title->en' => 'Broken',
            'title->ru' => 'Поломанная',
            'title->zh' => '好的',
            'slug' => 'broken'
        ]);
        Culet::create([
            'title->en' => 'None',
            'title->ru' => 'Не задано',
            'title->zh' => '好的',
            'slug' => 'none'
        ]);
        Culet::create([
            'title->en' => 'Small',
            'title->ru' => 'Маленькая',
            'title->zh' => '好的',
            'slug' => 'small'
        ]);
        Culet::create([
            'title->en' => 'Very small',
            'title->ru' => 'Очень маленькая',
            'title->zh' => '好的',
            'slug' => 'very_small'
        ]);
        Culet::create([
            'title->en' => 'Slightly large',
            'title->ru' => 'Больше средней',
            'title->zh' => '好的',
            'slug' => 'slightly_large'
        ]);
        Culet::create([
            'title->en' => 'Large',
            'title->ru' => 'Большая',
            'title->zh' => '好的',
            'slug' => 'large'
        ]);
        Culet::create([
            'title->en' => 'Very large',
            'title->ru' => 'Очень большая',
            'title->zh' => '好的',
            'slug' => 'very_strong'
        ]);


    }
}
