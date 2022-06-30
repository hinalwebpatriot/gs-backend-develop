<?php

use Illuminate\Database\Seeder;
use lenal\blocks\Models\DynamicPage;

class SeedDynamicPage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(['products-feed', 'products-detail'])->each(function($page) {
            if (!DynamicPage::query()->where('page', $page)->exists()) {
                DynamicPage::query()->create(['page' => $page]);
            }
        });
    }
}
