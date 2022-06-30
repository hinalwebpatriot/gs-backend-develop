<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class Tools
{
    public static function uri($name)
    {
        return str_replace(' ', '-', $name);
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public static function defaultStorage()
    {
        return Storage::disk(config('filesystems.cloud'));
    }

    public static function memory()
    {
        return memory_get_usage() / (1024 * 1024);
    }

    public static function memoryDiff($size)
    {
        return static::memory() - $size;
    }

    public static function getVersion()
    {
        return file_get_contents(base_path('version'));
    }
}