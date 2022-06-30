<?php

use Illuminate\Database\Seeder;
use lenal\blocks\Models\DynamicPage;

class DynamicPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            'diamonds-feed',
            'engagement-rings-feed',
            'wedding-rings-feed',
            'diamonds-detail',
            'engagement-rings-detail',
            'wedding-rings-detail',
            'contacts',
            'homepage'
        ];
        foreach ($pages as $code) {
            DynamicPage::firstOrCreate(['page' => $code]);
        }

    }
}
