<?php

namespace GSD\Core\Loaders;

use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class ProvidersLoaderTrait.
 */
trait ProvidersLoaderTrait
{

    /**
     * Loads only the Main Service Providers from the Containers.
     * All the Service Providers (registered inside the main), will be
     * loaded from the `boot()` function on the parent of the Main
     * Service Providers.
     *
     * @param $containerName
     */
    public function loadOnlyMainProvidersFromContainers($containerName)
    {
        $containerProvidersDirectory = AppGSD::getContainerProvidersPath($containerName);

        $this->loadProviders($containerProvidersDirectory);
    }

    /**
     * @param $directory
     */
    private function loadProviders($directory)
    {
        if (File::isDirectory($directory)) {
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                if (File::isFile($file)) {
                    // Загружаем только Main провайдер. Остальные должны загружаться через него.
                    if (Str::start($file->getFilename(), 'Main')) {
                        $serviceProviderClass = AppGSD::getFullClassNameByFileName($file->getPathname());
                        $this->loadProvider($serviceProviderClass);
                    }
                }
            }
        }
    }

    /**
     * @param $providerFullName
     */
    private function loadProvider($providerFullName)
    {
        App::register($providerFullName);
    }
}
