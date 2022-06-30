<?php


namespace GSD\Ship\Parents\Managers;


use GSD\Core\Abstracts\Managers\ClientManager as CoreClientManager;

/**
 * Клас служит менеджером для связи контейнера с другими контейнерами.
 * Методы должны возвращать данные других контейнеров которые возвращаются их ServerManagers.
 *
 * Class ClientManager
 * @package GSD\Ship\Parents\Managers
 */
class ClientManager extends CoreClientManager
{

}