<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\ProductCategory;
use Faker\Factory as Faker;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $names = [
            'Earrings',
            'Pendants',
            'Gift ideas',
            'Preset rings'
        ];
        foreach ($names as $name) {
            ProductCategory::create([
                'name->en' => $name,
                'name->ru' => $name,
                'name->zh' => $name,
                'slug->en' => str_slug($name, '-'),
                'slug->ru' => str_slug($name, '-'),
                'slug->zh' => str_slug($name, '-'),
                'image' =>
                    $faker->image(public_path('storage'), 400,300, null, false),
                'is_suggested' => rand(0,1)
            ]);
        }
    }
}
