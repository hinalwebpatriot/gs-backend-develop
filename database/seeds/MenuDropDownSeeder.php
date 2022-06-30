<?php

use Illuminate\Database\Seeder;
use lenal\additional_content\Models\MenuDropdownContent;

class MenuDropDownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuItems = [
            'diamonds',
            'engagement-rings',
            'wedding-rings'
        ];
        foreach ($menuItems as $menuItem) {
            foreach (array_keys(config('translatable.locales')) as $locale) {
                MenuDropdownContent::create([
                    'menu_item' => $menuItem,
                    'locale' => $locale
                ]);
            }
        }

    }
}
