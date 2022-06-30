<?php


namespace GSD\Ship\Core\Facades;


use GSD\Ship\Core\Abstracts\Facades\Facade;

/**
 * Class AppGSD
 * @package GSD\Ship\Core\Facades
 *
 * @method static array getContainersNames()
 * @method static string getContainerProvidersPath(string $container)
 * @method static string getContainerMigrationsPath(string $container)
 * @method static string getContainerApiRoutesPath(string $container)
 * @method static string getContainerCommandsPath(string $container)
 * @method static string getContainerConfigsPath(string $container)
 * @method static string getFullClassNameByFileName(string $filePath)
 */
class AppGSD extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \GSD\Ship\Core\Helpers\AppGSD::class;
    }

}