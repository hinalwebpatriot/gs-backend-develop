<?php


namespace GSD\Ship\Core\Loaders;


use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

/**
 * Trait RoutersLoaderTrait
 * @package GSD\Ship\Core\Loaders
 */
trait RoutersLoaderTrait
{

    /**
     * Загружает роуты контейнера
     * @param  string  $containerName
     */
    public function loadContainerRoutes(string $containerName): void
    {
        $fileRoutes = $this->getApiRoutesList($containerName);
        foreach ($fileRoutes as $file) {
            Route::group($this->getApiGroup($containerName, $file), $file);
        }
    }

    /**
     * Возвращает группу роутера API
     * @param  string  $containerName
     * @param  string  $routeFile
     * @return array
     */
    private function getApiGroup(string $containerName, string $routeFile): array
    {
        $fileExplode = explode('.', basename($routeFile));
        $version = $fileExplode[count($fileExplode) - 3];
        return [
            'as' => sprintf('api.%s.%s.', $version, Str::kebab($containerName)),
            'prefix' => sprintf('api/%s/%s', $version, Str::kebab($containerName)),
            'middleware' => 'api'
        ];
    }

    /**
     * Возвращает список файлов с роутами API
     * @param  string  $containerName
     * @return array
     */
    private function getApiRoutesList(string $containerName): array
    {
        $routesPath = AppGSD::getContainerApiRoutesPath($containerName);
        try {
            $files = File::files($routesPath);
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
        $list = [];
        foreach ($files as $file) {
            if (!Str::endsWith($file->getFilenameWithoutExtension(), 'json-prc')) {
                $list[] = $file->getPathname();
            }
        }
        return $list;
    }
}