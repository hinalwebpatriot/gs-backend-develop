<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use lenal\catalog\Mail\OrderDetailsMail;
use lenal\catalog\Mail\ServiceInvoiceMail;
use lenal\PriceCalculate\Facades\CountryVat;
use lenal\PriceCalculate\Facades\CurrencyRate;

/**
 * @property integer $id
 * @property string $alias
 * @property string $email
 * @property number $raw_price
 * @property number $inc_price
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $user_id
 * @property int $order_id
 * @property string $url
 * @property integer $is_sent
 *
 * @property InvoiceService[]|Collection $services
 */
class Invoice extends Model
{
    const STATUS_NEW = 'new';
    const STATUS_OPENED = 'opened';
    const STATUS_ORDER = 'order';
    const STATUS_PAYMENT = 'payment';
    const STATUS_PAYED = 'payed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SUCCESS = 'success';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::creating(function(Invoice $invoice) {
            $invoice->generateAlias();
            $invoice->status = $invoice::STATUS_NEW;
        });

        static::deleted(function(Invoice $invoice) {
            $invoice->services()->delete();
        });
    }

    public static function statuses()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_OPENED => 'Customer opened',
            self::STATUS_ORDER => 'Create order',
            self::STATUS_PAYMENT => 'Payment step',
            self::STATUS_PAYED => 'Payed',
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function services()
    {
        return $this->hasMany(InvoiceService::class);
    }

    public function calculatePrices()
    {
        $this->raw_price = 0;
        $this->inc_price = 0;

        foreach ($this->services as $service) {
            $this->raw_price += $service->rawPrice();
            $this->inc_price += $service->incPrice();
        }
    }

    public function generateAlias()
    {
        $this->alias = Str::random(40);
    }

    public function isActive()
    {
        return in_array($this->status, [self::STATUS_NEW, self::STATUS_OPENED, self::STATUS_ORDER, self::STATUS_PAYMENT]);
    }

    public function isOpened()
    {
        return $this->status == self::STATUS_OPENED;
    }

    public function openedStatus()
    {
        $this->changeStatus(self::STATUS_OPENED);
    }

    private function changeStatus($status, $save = true)
    {
        if ($this->status != $status) {
            $this->status = $status;

            if ($save) {
                $this->save();
            }
        }
    }

    public function paymentStatus()
    {
        $this->changeStatus(self::STATUS_PAYMENT);
    }

    public function paidStatus()
    {
        $this->changeStatus(self::STATUS_PAYED);
    }

    public function orderStatus()
    {
        $this->changeStatus(self::STATUS_ORDER, false);
    }

    public function getUrlAttribute()
    {
        if (!$this->alias) {
            return '-';
        }

        return config('app.frontend_url') . '/invoice/' . $this->alias;
    }

    public function isSentMail()
    {
        return $this->is_sent == 1;
    }

    public function convertedRawPrice()
    {
        return CurrencyRate::convertByUserCurrency($this->raw_price);
    }

    public function convertedIncPrice()
    {
        return CurrencyRate::convertByUserCurrency($this->getRealIncPrice());
    }

    protected function getRealIncPrice()
    {
        if (CountryVat::getCountryVat()) {
            return $this->inc_price;
        }
        return $this->raw_price;
    }
}
