<?php

use Illuminate\Database\Seeder;

class BaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ClaritySeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(CutSeeder::class);
        $this->call(FluorescenceSeeder::class);
        $this->call(PolishSeeder::class);
        $this->call(SymmetrySeeder::class);
        $this->call(ShapeSeeder::class);
        $this->call(CuletSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ArticleDetailSeeder::class);
        $this->call(ManufacturerSeeder::class);
        $this->call(StaticPagesSeeder::class);
        $this->call(DynamicPageSeeder::class);
        $this->call(ContactLinksSeeder::class);
        $this->call(MenuDropDownSeeder::class);
        $this->call(FAQSeeder::class);
        $this->call(MarginSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ShowRoomSeeder::class);
        $this->call(MainSliderSeeder::class);
        $this->call(CurrencyRateSeeder::class);
        $this->call(RingSizeSeeder::class);
        $this->call(MetalSeeder::class);
        $this->call(RingStyleSeeder::class);
        $this->call(RingCollectionSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
    }
}
