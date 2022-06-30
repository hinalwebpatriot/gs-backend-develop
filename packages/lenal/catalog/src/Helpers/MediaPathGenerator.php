<?php

namespace lenal\catalog\Helpers;



use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

/**
 * Class MediaPathGenerator
 *
 * @package lenal\catalog\Helpers
 */
class MediaPathGenerator extends DefaultPathGenerator
{

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    protected function getBasePath(Media $media): string
    {
        return $media->getAttribute('collection_name')
            . '/'
            . $media->getKey();
    }
}