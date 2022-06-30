<?php


namespace GSD\Containers\Order\Managers;


use GSD\Containers\Order\Data\Repositories\OrderRepository;
use GSD\Ship\Parents\Managers\ServerManager as ParentServerManager;
use GSD\Containers\Order\Models\Order;

/**
 * Class ServerManager
 * @package GSD\Containers\Order\Managers
 */
class ServerManager extends ParentServerManager
{
    /**
     * Возвращает модель заказа по идентификатору
     *
     * @param  int  $id
     *
     * @return Order|null
     */
    public static function getOrderById(int $id): ?Order
    {
        $repo = app(OrderRepository::class);
        return $repo->getById($id);
    }
}