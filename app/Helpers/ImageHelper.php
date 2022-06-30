<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Helpers\ImageFactory;

class ImageHelper
{
    public static function cropCloudImage($path, $width, $height, $prefix = '')
    {
        $cloudStorage = $storage = Storage::disk(config('filesystems.cloud'));
        $temporaryFile = tempnam(sys_get_temp_dir(), 'cloud-image');

        file_put_contents($temporaryFile, $cloudStorage->get($path));

        static::fitCrop($temporaryFile, $width, $height);

        $pathData = pathinfo($path);

        $path = $pathData['dirname'] . '/' . $prefix . $pathData['basename'];
        $storage->put($path, file_get_contents($temporaryFile));

        return $path;
    }

    /**
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $outputPath
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public static function fitCrop($path, $width, $height, $outputPath = '')
    {
        ImageFactory::load($path)
            ->optimize()
            ->fit(Manipulations::FIT_CROP, $width, $height)
            ->save($outputPath);
    }
}