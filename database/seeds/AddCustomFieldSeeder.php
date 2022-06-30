<?php

use Illuminate\Database\Seeder;

class AddCustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\lenal\catalog\Models\Products\Category::query()->get()->pluck('slug')->toArray() as $slug)
        \lenal\catalog\Models\Products\ProductField::query()->firstOrCreate([
            'category' => $slug,
        ], ['label' => ['en' => 'Stone total carat weight']]);
    }
}
