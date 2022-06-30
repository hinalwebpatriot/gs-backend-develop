<?php

namespace lenal\catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property integer $invoice_id
 * @property array $title
 * @property array $description
 * @property number $gst
 * @property number $price
 */
class InvoiceService extends Model
{
    use HasTranslations;

    protected $table = 'invoice_services';
    protected $guarded = [];
    public $timestamps = false;
    public $translatable = [
        'title',
        'description',
    ];

    public function incPrice()
    {
        return $this->price + $this->gst;
    }

    public function rawPrice()
    {
        return $this->price;
    }
}
