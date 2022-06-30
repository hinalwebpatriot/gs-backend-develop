<?php

namespace lenal\banners\Helpers;

use lenal\banners\Models\Banner;

class Banners
{
    public function findPageBanner($page, $hasImage = true) {
        return $hasImage
            ? Banner::where('page', $page)->where('image', '<>', '')->first()
            : Banner::where('page', $page)->first();
    }
}
