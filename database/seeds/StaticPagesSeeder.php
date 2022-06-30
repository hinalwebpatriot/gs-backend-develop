<?php

use Illuminate\Database\Seeder;
use lenal\static_pages\Models\StaticPage;
use Faker\Factory as Faker;

class StaticPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaticPage::all()->each(function($item){
            $item->title_translatable = ['en' => $item->title];
            $item->text_translatable = ['en' => $item->text];
            $item->save();
        });
    }
}
