<?php


namespace GSD\Containers\Referral\UI\CLI\Commands;


use GSD\Containers\Referral\Data\Repositories\TransactionRepository;
use GSD\Containers\Referral\Managers\ClientManager as OrderManager;
use GSD\Containers\Referral\Notifications\AlertNotification;
use GSD\Ship\Parents\Commands\Command;
use GSD\Ship\Parents\Notifications\Notification;

/**
 * Сигнализирует в телеграм о том что есть заказы не завершенные на которые есть реферальные транзакции
 * Если транзакция не проведена, а заказа не существует, то она будет удалена
 * Если транзакция не проведена, а заказ завершен успешно, то и транзакция будет проведена
 * Если транзакция не проведена, а заказ отменен, то транзакция будет удалена
 *
 * Class AlertCommand
 * @package GSD\Containers\Referral\UI\CLI\Commands
 */
class AlertCommand extends Command
{
    const ORDER_COMPLETE = 9;
    const ORDER_CANCEL   = 7;

    protected $signature   = 'referral:alert';
    protected $description = 'Alert in telegram if transaction not approved and older';

    private TransactionRepository $repo;

    /**
     * AlertCommand constructor.
     *
     * @param  TransactionRepository  $repo
     */
    public function __construct(TransactionRepository $repo)
    {
        parent::__construct();
        $this->repo = $repo;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        $ids = $this->repo->getDataNotApproved();
        $this->info(sprintf('%d - found', count($ids)));
        $messages = [];
        foreach ($ids as $transactionId => $orderId) {
            $order = OrderManager::getOrderById($orderId);
            if (!$order) {
                $this->repo->delete($transactionId);
                $messages[] = sprintf('Transaction #%d deleted because order #%d not found', $transactionId, $orderId);
                continue;
            }

            switch ($order->status_id) {
                case self::ORDER_COMPLETE:
                    $this->repo->approve($transactionId);
                    $messages[] = sprintf(
                        '✔ Transaction #%d approved because order #%d was complete',
                        $transactionId,
                        $orderId
                    );
                    break;
                case self::ORDER_CANCEL:
                    $this->repo->delete($transactionId);
                    $messages[] = sprintf(
                        '❌ Transaction #%d deleted because order #%d was cancel',
                        $transactionId,
                        $orderId
                    );
                    break;
                default:
                    if ($order->delivered_at && $order->delivered_at->addDays(35)->lt(now())) {
                        $messages[] = sprintf(
                            '👉🏻 Transaction #%d not approved because order #%d has "%s" status',
                            $transactionId,
                            $orderId,
                            $order->status
                        );
                    } elseif (!$order->delivered_at) {
                        $messages[] = sprintf(
                            '🚚 Transaction #%d not approved because order #%d not delivered',
                            $transactionId,
                            $orderId
                        );
                    }
            }
        }

        if ($messages) {
            \Illuminate\Support\Facades\Notification::route(
                'telegram',
                config('services.telegram-bot-api.manager-group')
            )->notify(new AlertNotification($messages));
            $this->info('Message sent');
        }
    }
}
