<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use lenal\catalog\Models\Products\Product;
use lenal\catalog\Models\Products\ProductStyle;

class ProductGenerateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Product::query()->count() > 0) {
            return ;
        }

        $categories = \lenal\catalog\Models\Products\Category::asArray();
        $brands = \lenal\catalog\Models\Products\Brand::asArray();
        $styles = \lenal\catalog\Models\Products\ProductStyle::all()->keyBy('id')->map(function(ProductStyle $model) {
            return ['slug' => $model->slug, 'title' => $model->title];
        });

        $catIds = \lenal\catalog\Models\Products\Category::all()->pluck('id')->toArray();
        $brandIds = \lenal\catalog\Models\Products\Brand::all()->pluck('id')->toArray();
        $styleIds = \lenal\catalog\Models\Products\ProductStyle::all()->pluck('id')->toArray();
        $metalIds = \lenal\catalog\Models\Rings\Metal::all()->pluck('id')->toArray();
        $shapeIds = \lenal\catalog\Models\Diamonds\Shape::all()->pluck('id')->toArray();
        $sizeIds = \lenal\catalog\Models\Products\ProductSize::all()->pluck('id')->toArray();

        for ($i = 1; $i <= 500; $i++) {
            $sku = 'TSR-' . $i;
            if (Product::query()->where('sku', $sku)->exists()) {
                continue;
            }

            $price = rand(100, 1000);
            $incPrice = $price + ($price * 0.1);
            $product = new Product();
            $product->sku = 'TSR-' . $i;
            $product->slug = $product->sku;
            $product->category_id = Arr::random($catIds);
            $product->brand_id = Arr::random($brandIds);
            $product->metal_id = Arr::random($metalIds);
            $product->style_id = Arr::random($styleIds);
            $product->shape_id = Arr::random($shapeIds);
            $product->gender = Arr::random(['male', 'female']);

            $category = $categories[$product->category_id];
            $brand = $brands[$product->brand_id];
            $style = $styles[$product->style_id];

            $product->raw_price = ceil($price);
            $product->inc_price = ceil($incPrice);
            $product->stone_size = Arr::random([1, 0.75, 1.5, 2, 0.5]);
            $product->setting_type = Arr::random(['2 Claw', '3 Claw', '4 Claw', '5 Claw', '6 Claw']);
            $product->side_setting_type = Arr::random(['Bead set', 'Claw set', 'Channel set Milgrain']);
            $product->min_size_id = Arr::random($sizeIds);
            $product->max_size_id = $product->min_size_id;
            $product->carat_weight = Arr::random(['0.72', '0.4', '0.17', '0.11', '0']);
            $product->average_ss_colour = Arr::random(['G/H', '']);
            $product->average_ss_clarity = Arr::random(['VS1/VS2', '']);
            $product->approx_stones = Arr::random(['8', '12', '24', '20', '0']);
            $product->band_width = Arr::random([2, 3.3, 1.6, 2.3, 2.8, 2.4]);

            $product->item_name = $category['name'] . ' ' . $brand['title'] . ' ' . $style['title'] . ' ' . $product->setting_type;
            $product->group_sku = Str::kebab(implode('/', [
                $category['slug'],
                $brand['slug'],
                $style['slug']
            ]));

            $product->save();
        }
    }
}
