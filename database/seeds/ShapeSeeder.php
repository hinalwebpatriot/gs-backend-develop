<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Shape;

class ShapeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shape::create([
            'title->en' => 'Round',
            'title->ru' => 'Круглый',
            'title->zh' => '一个好的',
            'preview_image' => '',
            'slug' => 'round'
        ]);
        Shape::create([
            'title->en' => 'Princess',
            'title->ru' => 'Принцесса',
            'title->zh' => '非常好',
            'preview_image' => '',
            'slug' => 'princess'
        ]);
        Shape::create([
            'title->en' => 'Emerald',
            'title->ru' => 'Изумруд',
            'title->zh' => '完美',
            'preview_image' => '',
            'slug' => 'emerald'
        ]);
        Shape::create([
            'title->en' => 'Asscher',
            'title->ru' => 'Ашер',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'asscher'
        ]);
        Shape::create([
            'title->en' => 'Marquise',
            'title->ru' => 'Маркиз',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'marquise'
        ]);
        Shape::create([
            'title->en' => 'Oval',
            'title->ru' => 'Овал',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'oval'
        ]);
        Shape::create([
            'title->en' => 'Radiant',
            'title->ru' => 'Радиант',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'radiant'
        ]);
        Shape::create([
            'title->en' => 'Pear',
            'title->ru' => 'Груша',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'pear'
        ]);
        Shape::create([
            'title->en' => 'Heart',
            'title->ru' => 'Сердце',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'heart'
        ]);
        Shape::create([
            'title->en' => 'Cushion',
            'title->ru' => 'Кушон',
            'title->zh' => '心和箭',
            'preview_image' => '',
            'slug' => 'cushion'
        ]);
    }
}
