<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Clarity;

class ClaritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clarity::create([
            'title' => 'FL',
            'slug' => 'fl',
            'value' => 1
        ]);
        Clarity::create([
            'title' => 'IF',
            'slug' => 'if',
            'value' => 2
        ]);
        Clarity::create([
            'title' => 'VVS1',
            'slug' => 'vvs1',
            'value' => 3
        ]);
        Clarity::create([
            'title' => 'VVS2',
            'slug' => 'vvs2',
            'value' => 4
        ]);
        Clarity::create([
            'title' => 'VS1',
            'slug' => 'vs1',
            'value' => 5
        ]);
        Clarity::create([
            'title' => 'VS2',
            'slug' => 'vs2',
            'value' => 6
        ]);
        Clarity::create([
            'title' => 'SI1',
            'slug' => 'si1',
            'value' => 7
        ]);
        Clarity::create([
            'title' => 'SI2',
            'slug' => 'si2',
            'value' => 8
        ]);
    }
}
