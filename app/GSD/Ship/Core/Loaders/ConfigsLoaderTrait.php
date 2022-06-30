<?php


namespace GSD\Ship\Core\Loaders;


use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

/**
 * Trait ConfigsLoaderTrait
 * @package GSD\Ship\Core\Loaders
 */
trait ConfigsLoaderTrait
{
    /**
     * @param $containerName
     */
    public function loadContainerConfigs($containerName): void
    {
        $containerConfigsDirectory = AppGSD::getContainerConfigsPath($containerName);
        if (File::isDirectory($containerConfigsDirectory)) {
            try {
                $files = File::files($containerConfigsDirectory);
            } catch (DirectoryNotFoundException $e) {
                return;
            }
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $this->mergeConfigFrom(
                        $file->getRealPath(),
                        sprintf('%s.%s', Str::lower($containerName), $file->getFilenameWithoutExtension())
                    );
                }
            }
        }
    }

}