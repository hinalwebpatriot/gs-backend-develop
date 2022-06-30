<?php

use Illuminate\Database\Seeder;
use lenal\blocks\Models\DynamicPage;
use lenal\blocks\Models\StaticBlock;
use lenal\catalog\Models\Diamonds\Diamond;
use lenal\catalog\Models\Rings\EngagementRing;
use lenal\catalog\Models\Rings\WeddingRing;

class StaticBlockRecommendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // recommend products
        $pages = DynamicPage::whereIn(
                'page',
                [
                    'diamonds-detail',
                    'engagement-rings-detail',
                    'wedding-rings-detail',
                    'homepage'
                ]
        )->get();
        foreach ($pages as $page) {
            if ($page->recommendProducts) {
                continue;
            }
            $recommendBlock = StaticBlock::create([
                'title' => [
                    'en' => 'Worth paying attention',
                    'ru' => 'Стоит обратить внимание',
                    'zh' => '值得關注',
                ],
                'text' => [
                    'en' => 'Discover our best selling rings of 2018. Scroll through to find your perfect ring.',
                    'ru' => 'Discover our best selling rings of 2018. Scroll through to find your perfect ring.',
                    'zh' => 'Discover our best selling rings of 2018. Scroll through to find your perfect ring.',
                ],
                'block_type' => 'recommend-products'
            ]);
            $diamonds = Diamond::inRandomOrder()->take(3)->get();
            $engRings = EngagementRing::inRandomOrder()->take(3)->get();
            $wedRings = WeddingRing::inRandomOrder()->take(3)->get();
            $recommendBlock->blockDiamonds()->sync($diamonds);
            $recommendBlock->blockEngagementRings()->sync($engRings);
            $recommendBlock->blockWeddingRings()->sync($wedRings);
            $recommendBlock->save;
            $page->recommendProducts()->save($recommendBlock);
            $page->save;
        }
    }
}
