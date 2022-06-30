<?php

namespace lenal\subscribe\Helpers;

use lenal\banners\Models\Banner;
use lenal\subscribe\Jobs\SubscriberToHubspotJob;
use lenal\subscribe\Models\Subscribe;

class SubscribeHelper
{
    public function getSubscribeForm()
    {
        $bannerData = Banner::wherePage('subscribe-form')->first();
        $form['banner'] = null;
        if ($bannerData) {
            $banners = $bannerData->getMedia('subscribe-form');
            foreach ($banners as $media) {
                $lang = $media->getCustomProperty('language')? : config('translatable.fallback_locale');
                $form['banner'][$lang] = $media->getFullUrl();
            }
            $locale = app()->getLocale();
            $fallbackLocale = config('translatable.fallback_locale');
            $form['banner'] = isset($form['banner'][$locale])
                ? $form['banner'][$locale]
                : isset($form['banner'][$fallbackLocale])? $form['banner'][$fallbackLocale]: null;
        }
        $form['type'] = ['sale', 'discounts', 'new_collection'];
        $form['gender'] = ['man', 'woman'];
        return $form;
    }

    public function saveSubscriber($data)
    {
        Subscribe::create([
            'email' => $data['email'],
            'type' => $data['type'],
            'gender' => $data['gender']
        ]);
        SubscriberToHubspotJob::dispatch($data['email']);
    }
}
