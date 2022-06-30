<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configMediaDomain();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function configMediaDomain()
    {
        if (env('FILESYSTEM_CLOUD') != 's3' || !env('IMG_HOST')) {
            return ;
        }

        if (request()->header('X-DIAMOND-TOKEN')) {
            return ;
        }

        $excludeUris = [
            'test/*',
            'nova/*',
            'nova-api/*',
        ];

        if ($excludeUris) {
            foreach ($excludeUris as $uri) {
                if (request()->is($uri)) {
                    return ;
                }
            }
        }

        config(['medialibrary.s3.domain' => env('IMG_HOST')]);
        config(['filesystems.disks.s3.url' => env('IMG_HOST')]);
    }
}
