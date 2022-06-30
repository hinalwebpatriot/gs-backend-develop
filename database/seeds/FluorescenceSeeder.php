<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Fluorescence;

class FluorescenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fluorescence::create([
            'title->en' => 'None',
            'title->ru' => 'Нет',
            'title->zh' => '没有',
            'slug' => 'none',
            'value' => 1
        ]);
        Fluorescence::create([
            'title->en' => 'Faint',
            'title->ru' => 'Тусклое',
            'title->zh' => '晕',
            'slug' => 'faint',
            'value' => 2
        ]);
        Fluorescence::create([
            'title->en' => 'Medium',
            'title->ru' => 'Среднее',
            'title->zh' => '介质',
            'slug' => 'medium',
            'value' => 3
        ]);
        Fluorescence::create([
            'title->en' => 'Strong',
            'title->ru' => 'Сильное',
            'title->zh' => '强大',
            'slug' => 'strong',
            'value' => 4
        ]);
        Fluorescence::create([
            'title->en' => 'Very Strong',
            'title->ru' => 'Очень сильное',
            'title->zh' => '非常强壮',
            'slug' => 'very_strong',
            'value' => 5
        ]);
    }
}
