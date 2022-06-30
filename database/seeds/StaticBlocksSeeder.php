<?php

use Illuminate\Database\Seeder;
use lenal\blocks\Models\DynamicPage;
use lenal\blocks\Models\StaticBlock;
use Faker\Factory as Faker;
use lenal\catalog\Models\Rings\EngagementRing;

class StaticBlocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $fakerRU = Faker::create('ru_RU');
        $fakerZH = Faker::create('zh_TW');

        // completeLookBlock , Why GS Diamonds
        $page = DynamicPage::wherePage('diamonds-detail')->first();
        if (!$page->completeLookBlock && !$page->descriptionBlock) {
            $blockComplete = StaticBlock::create([
                'title' => [
                    'en' => 'Complete the look',
                    'ru' => 'Дополните образ',
                    'zh' => 'Complete the look',
                ],
                'block_type' => 'complete-look',
                'video_link' => 'https://vimeo.com/311572542'
            ]);
            $blockDesc = StaticBlock::create([
                'title' => [
                    'en' => 'Why GS Diamonds',
                    'ru' => 'Почему GS Diamonds',
                    'zh' => 'Why GS Diamonds',
                ],
                'text' => [
                    'en' => $faker->realText(300, $indexSize = 5),
                    'ru' => $fakerRU->realText(300, $indexSize = 5),
                    'zh' => $fakerZH->realText(300, $indexSize = 5),
                ],
                'block_type' => 'description',
            ]);
            $page->completeLookBlock()->save($blockComplete);
            $page->descriptionBlock()->save($blockDesc);
            $page->save;
        }


        $page = DynamicPage::wherePage('homepage')->first();
        if (!$page->occasionSpecial) {
            $occasionBlock = StaticBlock::create([
                'title' => [
                    'en' => 'Let Your occasion be special',
                    'ru' => 'Let Your occasion be special',
                    'zh' => 'Let Your occasion be special',
                ],
                'block_type' => 'occasion-special'
            ]);
            $engRings = EngagementRing::inRandomOrder()->take(4)->get();
            $occasionBlock->blockEngagementRings()->sync($engRings);
            $occasionBlock->save;
            $page->occasionSpecial()->save($occasionBlock);
            $page->save;
        }

    }
}
