<?php

use Illuminate\Database\Seeder;
use lenal\additional_content\Models\FAQ;
use Faker\Factory as Faker;

class FAQSeeder extends Seeder
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
        $fakerZH= Faker::create('zh_TW');
        for ($i = 0; $i < 5; $i++) {
            FAQ::create([
                'title' => [
                    'en' => $faker->realText(100, $indexSize = 5),
                    'ru' => $fakerRU->realText(100, $indexSize = 5),
                    'zh' => $fakerZH->realText(100, $indexSize = 5)
                ],
                'text' => [
                    'en' => $faker->realText(300, $indexSize = 5),
                    'ru' => $fakerRU->realText(300, $indexSize = 5),
                    'zh' => $fakerZH->realText(300, $indexSize = 5)
                ]
            ]);
        }
    }
}
