<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Manufacturer;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufacturers = [
            [
                'title' => 'Rapnet',
                'slug'  => 'rapnet',
            ],
            [
                'title' => 'Jbbrothers',
                'slug'  => 'jbbrothers',
            ],
            [
                'title' => 'MID_INV',
                'slug'  => 'mid',
            ],
            [
                'title' => 'Kiran',
                'slug'  => 'kiran',
            ],
            [
                'title' => 'SheetalStock',
                'slug'  => 'sheetalstock',
            ],
            [
                'title' => 'San',
                'slug'  => 'san',
            ],
            [
                'title' => 'StarraysReport',
                'slug'  => 'starraysreport',
            ],
            [
                'title' => 'ZS',
                'slug'  => 'zs',
            ],
            [
                'title' => 'SRK',
                'slug'  => 'srk',
            ],
            [
                'title' => 'GSD',
                'slug'  => 'gsd',
            ]
        ];

        foreach ($manufacturers as $manufacturer) {
            if (!Manufacturer::query()->where('slug', $manufacturer['slug'])->exists()) {
                Manufacturer::query()->create($manufacturer);
            }
        }
    }
}
