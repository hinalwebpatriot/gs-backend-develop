<?php

namespace GSD\Ship\Core\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Class AppGSD
 * @package GSD\Ship\Core\Helpers
 *
 * Класс которые реализуе хелперы для функционирования архитектуры
 */
class AppGSD
{
    /**
     * Возвращает список контейнеров приложения
     * @return array
     */
    public function getContainersNames(): array
    {
        $containersNames = [];

        foreach (File::directories($this->getContainerPath()) as $containerPath) {
            $containersNames[] = basename($containerPath);
        }

        return $containersNames;
    }

    /**
     * Возвращает путь к провайдерам контейнера
     *
     * @param  string  $container  имя контейнера
     *
     * @return string
     */
    public function getContainerProvidersPath(string $container): string
    {
        return sprintf('%s/%s/Providers', $this->getContainerPath(), $container);
    }

    /**
     * Возвращает путь к миграциям контейнера
     *
     * @param  string  $container  имя контейнера
     *
     * @return string
     */
    public function getContainerMigrationsPath(string $container): string
    {
        return sprintf('%s/%s/Data/Migrations', $this->getContainerPath(), $container);
    }

    /**
     * Возвращает путь к конфигам контейнера
     *
     * @param  string  $container  имя контейнера
     *
     * @return string
     */
    public function getContainerConfigsPath(string $container): string
    {
        return sprintf('%s/%s/Configs', $this->getContainerPath(), $container);
    }

    /**
     * Возвращает путь к API роутам контейнера
     *
     * @param  string  $container  имя контейнера
     *
     * @return string
     */
    public function getContainerApiRoutesPath(string $container): string
    {
        return sprintf('%s/%s/UI/API/Routes', $this->getContainerPath(), $container);
    }

    /**
     * Возвращает путь к командам контейнера
     *
     * @param  string  $container  имя контейнера
     *
     * @return string
     */
    public function getContainerCommandsPath(string $container): string
    {
        return sprintf('%s/%s/UI/CLI/Commands', $this->getContainerPath(), $container);
    }

    /**
     * Возвращает полное имя класса с неймспейсом php файла
     *
     * @param  string  $filePath
     *
     * @return string
     */
    public function getFullClassNameByFileName(string $filePath): string
    {
        $info = pathinfo($filePath);

        $className = $info['filename'];
        $namespace = str_replace('/', '\\', Str::after($info['dirname'], 'app/'));

        return sprintf('%s\\%s', $namespace, $className);
    }

    /**
     * Возвращает путь к приложению
     * @return string
     */
    private function getAppPath(): string
    {
        return app_path('GSD');
    }

    /**
     * Возвращает путь к контейнерам приложения
     * @return string
     */
    private function getContainerPath(): string
    {
        return sprintf('%s/%s', $this->getAppPath(), 'Containers');
    }
}