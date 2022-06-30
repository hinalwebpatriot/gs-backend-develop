<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::create([
            'title' => 'D',
            'slug' => 'd',
            'value' => 1
        ]);
        Color::create([
            'title' => 'E',
            'slug' => 'e',
            'value' => 2
        ]);
        Color::create([
            'title' => 'F',
            'slug' => 'f',
            'value' => 3
        ]);
        Color::create([
            'title' => 'G',
            'slug' => 'g',
            'value' => 4
        ]);
        Color::create([
            'title' => 'H',
            'slug' => 'h',
            'value' => 5
        ]);
        Color::create([
            'title' => 'I',
            'slug' => 'i',
            'value' => 6
        ]);
        Color::create([
            'title' => 'J',
            'slug' => 'j',
            'value' => 7
        ]);
        Color::create([
            'title' => 'K',
            'slug' => 'k',
            'value' => 8
        ]);
        Color::create([
            'title' => 'L',
            'slug' => 'l',
            'value' => 9
        ]);
        Color::create([
            'title' => 'M',
            'slug' => 'm',
            'value' => 10
        ]);
    }
}
