<?php

use Illuminate\Database\Seeder;

class MetalSeeder extends Seeder
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
                    'en' => '18ct Yellow-White Gold',
                ],
                'slug' => '18ct-yellow-white-gold',
                'engagement_off' => true
            ],
            [
                'title' => [
                    'en' => '18ct Rose-White Gold',
                ],
                'slug' => '18ct-rose-white-gold',
                'engagement_off' => true
            ],
        ])->each(function ($metal) {
            \lenal\catalog\Models\Rings\Metal::create($metal);
        });
    }
}
