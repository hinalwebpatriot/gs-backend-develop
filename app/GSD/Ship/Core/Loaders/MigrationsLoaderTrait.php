<?php

namespace GSD\Core\Loaders;

use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\File;

/**
 * Class MigrationsLoaderTrait.
 */
trait MigrationsLoaderTrait
{

    /**
     * @param $containerName
     */
    public function loadContainerMigrations($containerName)
    {
        $containerMigrationDirectory = AppGSD::getContainerMigrationsPath($containerName);
        if (File::isDirectory($containerMigrationDirectory)) {

            $this->loadMigrationsFrom($containerMigrationDirectory);
        }
    }
}
