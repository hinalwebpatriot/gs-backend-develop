<?php


namespace GSD\Containers\Referral\Nova;


use GSD\Containers\Referral\Nova\Actions\PayoutTransaction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

/**
 * Ресурс для новы.
 * Покупатели которые отправили реферальные коды
 *
 * Class TowerCustomer
 * @package App\GSD\Containers\Referral\Nova
 */
class ReferralCustomer extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Referral\Models\ReferralCustomer::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf('%s %s - %s', $this->first_name, $this->last_name, $this->email);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'email', 'phone', 'first_name', 'last_name',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Customers';
    }

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Email', 'email')->rules(['required'])->sortable(),
            Text::make('Phone', 'phone')->nullable(),
            Text::make('First Name', 'first_name')->rules(['required', 'min:2'])->sortable(),
            Text::make('Last Name', 'last_name')->nullable(),
            Number::make('Balance', 'balance')->exceptOnForms()->nullable()->sortable(),
            HasMany::make('Promo codes', 'promoCodes', ReferralPromoCode::class),
            HasMany::make('Transactions', 'transactions', ReferralTransaction::class),
            HasMany::make('Payouts', 'payouts', ReferralPayoutTransaction::class),
            DateTime::make('Created At', 'created_at')->onlyOnDetail()
        ];
    }

    /**
     * @param  Request  $request
     *
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            app(PayoutTransaction::class)
                ->confirmText('Indicate the amount to be debited')
                ->confirmButtonText('Payout')
                ->cancelButtonText('Cancel'),
        ];
    }
}
