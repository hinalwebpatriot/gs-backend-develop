<?php

use Illuminate\Database\Seeder;

class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'title' => [
                    'en' => 'Sale',
                ],
                'slug'  => 'sale',
                'sort'  => 100,
            ],
            [
                'title' => [
                    'en' => 'Special Offers',
                ],
                'slug'  => 'special',
                'sort'  => 200,
            ],
            [
                'title' => [
                    'en' => 'Valentine day offers',
                ],
                'slug'  => 'valentine',
                'sort'  => 300,
            ],
            [
                'title'    => [
                    'en' => 'Discount up to 50% off',
                ],
                'slug'     => 'discount',
                'sort'     => 400,
                'is_sale'  => true,
                'discount' => 50
            ],
        ])->each(function ($offer) {
            \lenal\offers\Models\Offer::create($offer);
        });
    }
}
