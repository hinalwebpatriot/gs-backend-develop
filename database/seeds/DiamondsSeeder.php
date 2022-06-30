<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class DiamondsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $sku_random_number = rand(10000, 999999999);
            Diamond::create([
                'title->en' => rand(0.1, 8) . ' carat heart diamond',
                'title->ru' => 'Бриллиант в виде сердца, ' . rand(0.1, 8) . ' карат',
                'title->zh' => '亮圆形切割，' . rand(0.1, 8) . '克拉',
                'slug' => 'sku-' . $sku_random_number,
                'sku' => $sku_random_number,
                'shape_id' => rand(1, 2),
                'clarity_id' => rand(1, 8),
                'color_id' => rand(1, 10),
                'cut_id' => rand(1, 4),
                'symmetry_id' => rand(1, 3),
                'polish_id' => rand(1, 3),
                'fluorescence_id' => rand(1, 5),
                'raw_price' => rand(1, 5),
                'carat' => rand(1, 5),
                'manufacturer_id' => rand(1, 5),
            ]);
            EngagementRing::create([
                'slug' => 'sku-' . $sku_random_number,
                'sku' => $sku_random_number,
                'band_width' => rand(1, 2),
                'raw_price' => rand(1, 8),
                'ring_style_id' => rand(1, 10),
                'ring_collection_id' => rand(1, 3),
                'side_stones_id' => rand(1, 5)
            ]);
            WeddingRing::create([
                'slug' => 'sku-' . $sku_random_number,
                'sku' => $sku_random_number,
                'band_width' => rand(1, 2),
                'raw_price' => rand(1, 8),
                'ring_style_id' => rand(1, 10),
                'ring_collection_id' => rand(1, 3),
                'side_stones_id' => rand(1, 5)
            ]);
        }
    }
}
