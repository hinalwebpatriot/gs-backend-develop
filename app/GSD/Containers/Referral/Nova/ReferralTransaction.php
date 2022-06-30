<?php


namespace GSD\Containers\Referral\Nova;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;

/**
 * Ресурс для новы.
 * Транзакции с промокодами
 *
 * Class ReferralTransaction
 * @package App\GSD\Containers\Referral\Nova
 */
class ReferralTransaction extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Referral\Models\ReferralTransaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf(
            '%s - %s',
            $this->order_id ?: $this->tower_id,
            $this->owner ? $this->owner->email : 'User Unknown');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'order_id', 'tower_id',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Transactions';
    }

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Owner', 'owner', ReferralCustomer::class)
                ->rules(['required'])
                ->searchable(),
            BelongsTo::make('Promo Code', 'code', ReferralPromoCode::class)
                ->rules(['required'])
                ->searchable(),
            Number::make('Order ID', 'order_id')->nullable(),
            Number::make('Tower ID', 'tower_id')->nullable(),
            Number::make('Order Sum', 'order_sum')->rules(['required']),
            Number::make('Payment', 'payment')->rules(['required']),
            DateTime::make('Approved', 'approved_at')->nullable()->readonly($this->isApproved),
            DateTime::make('Created At', 'created_at')->onlyOnDetail(),
        ];
    }
}