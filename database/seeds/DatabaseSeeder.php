<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Paysystem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SeedDynamicPage::class);
        $this->call(ProductOptionsSeeder::class);
        //$this->call(ProductGenerateSeeder::class);
        $this->call(AlipayPaymentSeeder::class);
        $this->call(ManufacturerSeeder::class);
    }
}
