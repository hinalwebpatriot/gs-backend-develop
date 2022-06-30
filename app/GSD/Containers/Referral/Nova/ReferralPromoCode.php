<?php


namespace GSD\Containers\Referral\Nova;


use GSD\Containers\Referral\Nova\Actions\UsePromoCodeTransaction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;

/**
 * Ресурс для новы.
 * Выданные промокоды
 *
 * Class ReferralPromoCode
 * @package App\GSD\Containers\Referral\Nova
 */
class ReferralPromoCode extends \App\Nova\Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \GSD\Containers\Referral\Models\ReferralPromoCode::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @return string
     */
    public function title(): string
    {
        return sprintf('%s - %s', $this->code, $this->recipient_email);
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'code', 'recipient_email', 'recipient_first_name', 'recipient_last_name',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Promo codes';
    }

    /**
     * @inheritDoc
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Owner', 'owner', ReferralCustomer::class)->rules(['required']),
            Text::make('Code', 'code'),
            Boolean::make('Used', 'is_used'),
            DateTime::make('Created At', 'created_at')->onlyOnDetail(),
           new Panel('Recipient', [
                Text::make('Email', 'recipient_email')->rules(['required']),
                Text::make('First Name', 'recipient_first_name')->rules(['required']),
                Text::make('Last Name', 'recipient_last_name')->nullable(),
            ]),
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
            app(UsePromoCodeTransaction::class)
                ->confirmText('Use referral promo code')
                ->confirmButtonText('Use')
                ->cancelButtonText('Cancel'),
        ];
    }
}