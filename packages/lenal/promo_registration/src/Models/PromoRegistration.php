<?php

namespace lenal\promo_registration\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 */
class PromoRegistration extends Model
{
    public $fillable = ['first_name', 'last_name', 'email'];

    public static function boot()
    {
        parent::boot();

        parent::saving(function(self $model) {
            $name = substr($model->email, 0, strpos($model->email, '@'));

            if (!$model->first_name) {
                $model->first_name = $name;
            }

            if (!$model->last_name) {
                $model->last_name = $name;
            }
        });
    }
}
