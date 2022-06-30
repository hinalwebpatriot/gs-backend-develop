<?php

use Illuminate\Database\Seeder;
use lenal\MainSlider\Models\MainSlider;
use lenal\MainSlider\Models\MainSliderSlide;
use lenal\MainSlider\Models\MainSliderVideo;
use Faker\Factory as Faker;

class MainSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $slider = MainSlider::firstOrCreate([
            'id' => 1,
            'title' => 'default'
        ]);
        $slider_video = MainSliderVideo::create([
            'title' => $faker->sentence,
            'youtube_link->en' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
            'youtube_link->ru' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
            'youtube_link->zh' => 'https://www.youtube.com/watch?v=6Stj0jKBh8M',
        ]);

        $slider_slides = factory(MainSliderSlide::class, 5)->create();

        $slider->video()->associate($slider_video);
        $slider->slides()->sync($slider_slides);
        $slider->save();
    }
}
