<?php

use Illuminate\Database\Seeder;
use lenal\catalog\Models\FilterDescription;

class FilterDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FilterDescription::query()->truncate();
        $filters = [
            'diamonds' => [
                'shape',
                'carat',
                'cut',
                'price',
                'clarity',
                'color',
                'polish',
                'symmetry',
                'fluorescence',
                'depth',
                'table',
                'size_ratio'
            ],
            'engagement-rings' => [
                'metal',
                'price',
                'style',
                'shape',
                'size',
                'offer',
                'collection'
            ],
            'wedding-rings' => [
                'gender',
                'metal',
                'price',
                'style',
                'shape',
                'size',
                'offer'
            ],
        ];
        $locales = array_keys(config('translatable.locales'));
        foreach ($filters as $feed => $slugs) {
            foreach ($slugs as $slug) {
                $videos = [];
                foreach ($locales as $locale) {
                    $videos[$locale] = 'https://vimeo.com/35905392';
                }
                FilterDescription::create([
                    'slug' => $slug,
                    'product_feed' => $feed,
                    'video_link' => $videos
                ]);
            }
        }
    }
}
