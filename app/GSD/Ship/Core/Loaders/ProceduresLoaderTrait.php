<?php


namespace GSD\Ship\Core\Loaders;


use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

/**
 * Trait ProceduresLoaderTrait
 * @package GSD\Ship\Core\Loaders
 */
trait ProceduresLoaderTrait
{

    /**
     * Загружает JSON-PRC роуты контейнера
     * @param  string  $containerName
     */
    public function loadContainerProcedures(string $containerName): void
    {
        $fileRoutes = $this->getPrcRoutesList($containerName);
        foreach ($fileRoutes as $file) {
            Route::group($this->getRouteGroup($containerName, $file), $file);
        }
    }

    /**
     * Возвращает группу роутера API
     * @param  string  $containerName
     * @param  string  $routeFile
     * @return array
     */
    private function getRouteGroup(string $containerName, string $routeFile): array
    {
        $fileExplode = explode('.', basename($routeFile));
        $version = $fileExplode[count($fileExplode) - 3];
        return [
            'prefix'     => sprintf('api/prc/%s', $version),
            //'middleware' => 'rpc.endpoint'
        ];
    }

    /**
     * Возвращает список файлов с роутами API
     * @param  string  $containerName
     * @return array
     */
    private function getPrcRoutesList(string $containerName): array
    {
        $routesPath = AppGSD::getContainerApiRoutesPath($containerName);
        try {
            $files = File::files($routesPath);
        } catch (DirectoryNotFoundException $e) {
            return [];
        }
        $list = [];
        foreach ($files as $file) {
            if (Str::endsWith($file->getFilenameWithoutExtension(), 'json-prc')) {
                $list[] = $file->getPathname();
            }
        }
        return $list;
    }
}