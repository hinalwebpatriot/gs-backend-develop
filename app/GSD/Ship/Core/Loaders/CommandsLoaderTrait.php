<?php


namespace GSD\Ship\Core\Loaders;


use GSD\Ship\Core\Facades\AppGSD;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

/**
 * Trait CommandsLoaderTrait
 * @package GSD\Ship\Core\Loaders
 */
trait CommandsLoaderTrait
{
    /**
     * @param $containerName
     */
    public function loadContainerCommands($containerName): void
    {
        $containerConfigsDirectory = AppGSD::getContainerCommandsPath($containerName);
        if (File::isDirectory($containerConfigsDirectory)) {
            try {
                $files = File::files($containerConfigsDirectory);
            } catch (DirectoryNotFoundException $e) {
                return;
            }
            $commands = [];
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $commands[] = AppGSD::getFullClassNameByFileName($file->getRealPath());
                }
            }

            $this->commands($commands);
        }
    }
}