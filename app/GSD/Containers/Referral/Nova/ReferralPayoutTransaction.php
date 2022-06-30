<?php


namespace GSD\Containers\Referral\Nova;


use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;

/**
 * Ресурс для новы.
 * Выплаты рефералам
 *
 * Class ReferralPayoutTransaction
 * @package App\GSD\Containers\Referral\Nova
 */
class ReferralPayoutTransaction extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Referral\Models\ReferralPayoutTransaction::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf(
            '%s - %f',
            $this->owner ? $this->owner->email : 'User Unknown',
            $this->payout);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'owner.email', 'owner.first_name', 'owner.last_name'
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Payout Transactions';
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
                ->exceptOnForms(),
            Number::make('Payout', 'payout')
                ->rules(['required'])
                ->exceptOnForms(),
            DateTime::make('Created At', 'created_at')->exceptOnForms(),
        ];
    }

    public static function indexQuery(Request $request, $query)
    {
        $query->select('referral_payout_transactions.*', 'owner.email', 'owner.first_name', 'owner.last_name');
        $query->join('referral_customers as owner', 'owner.id', '=', 'owner_id');
        return $query;
    }
}