<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\Products\Brand;
use lenal\catalog\Models\Products\Category;
use lenal\catalog\Models\Products\ProductSize;
use lenal\catalog\Models\Products\ProductStyle;
use lenal\catalog\Models\Rings\EngagementRingStyle;
use lenal\catalog\Models\Rings\RingCollection;
use lenal\catalog\Models\Rings\RingSize;

class ProductOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (ProductSize::query()->count() == 0) {
            RingSize::all()->each(function(RingSize $ringSize) {
                $model = new ProductSize();
                $model->slug = $ringSize->slug;
                $model->title = json_decode($ringSize->size, true);
                $model->save();
            });
        }

        if (ProductStyle::query()->count() == 0) {
            EngagementRingStyle::all()->each(function(EngagementRingStyle $ringStyle) {
                $model = new ProductStyle();
                $model->slug = $ringStyle->slug;
                $model->title = $ringStyle->title;
                $model->save();
            });
        }

        if (Brand::query()->count() == 0) {
            RingCollection::all()->each(function(RingCollection $ringCollection) {
                Brand::query()->create($ringCollection->only('slug', 'title'));
            });
        }

        if (Category::query()->count() == 0) {
            collect(['earrings', 'pendant', 'bracelets'])->each(function($alias) {
                $model = new Category();
                $model->slug = $alias;
                $model->name = ucfirst($alias);
                $model->save();
            });
        }
    }
}
