<?php


namespace GSD\Containers\Referral\Managers;


use GSD\Containers\Order\Managers\ServerManager as OrderManager;
use GSD\Containers\Referral\DTO\OrderDTO;
use GSD\Containers\Subscribe\Managers\ServerManager as SubscribeManager;
use GSD\Ship\Parents\Managers\ClientManager as ParentClientManager;

class ClientManager extends ParentClientManager
{
    /**
     * Отправляет на подписку клиента
     *
     * @param  string  $email
     */
    public static function subscribeCustomer(string $email): void
    {
        SubscribeManager::subscribeForReferral($email);
    }

    /**
     * Возвращает данные заказа в виде ДТО
     *
     * @param  int  $id
     *
     * @return OrderDTO|null
     */
    public static function getOrderById(int $id): ?OrderDTO
    {
        $order = OrderManager::getOrderById($id);
        if (!$order) {
            return null;
        }

        return new OrderDTO([
            'id'           => $order->id,
            'created_at'   => $order->created_at,
            'delivered_at' => $order->delivered_at,
            'status_id'    => $order->status,
            'status'       => $order->statusOrder->title
        ]);
    }
}