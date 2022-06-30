<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Symmetry;

class SymmetrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Symmetry::create([
            'title->en' => 'Excellent',
            'title->ru' => 'Отличная',
            'title->zh' => '优',
            'slug' => 'excellent',
            'value' => 1
        ]);
        Symmetry::create([
            'title->en' => 'Very good',
            'title->ru' => 'Очень хорошая',
            'title->zh' => '非常好',
            'slug' => 'very_good',
            'value' => 2
        ]);
        Symmetry::create([
            'title->en' => 'Good',
            'title->ru' => 'Хорошая',
            'title->zh' => '好的',
            'slug' => 'good',
            'value' => 3
        ]);
        Symmetry::create([
            'title->en' => 'Fair',
            'title->ru' => 'Удовлетворительная',
            'title->zh' => '满意的',
            'slug' => 'fair',
            'value' => 4
        ]);
    }
}
