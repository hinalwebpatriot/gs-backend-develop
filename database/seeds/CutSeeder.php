<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Cut;

class CutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cut::create([
            'title->en' => 'Excellent',
            'title->ru' => 'Отличная',
            'title->zh' => '一个好的',
            'slug' => 'excellent',
            'value' => 1
        ]);
        Cut::create([
            'title->en' => 'Very good',
            'title->ru' => 'Очень хорошая',
            'title->zh' => '非常好',
            'slug' => 'very_good',
            'value' => 2
        ]);
        Cut::create([
            'title->en' => 'Good',
            'title->ru' => 'Хорошая',
            'title->zh' => '完美',
            'slug' => 'good',
            'value' => 3
        ]);
        Cut::create([
            'title->en' => 'Fair',
            'title->ru' => 'Удовлетворительная',
            'title->zh' => '心和箭',
            'slug' => 'fair',
            'value' => 4
        ]);
    }
}
