<?php

namespace App\Policies;
use App\User;
use Eminiarts\NovaPermissions\Policies\Policy;

class DiamondPolicy extends Policy
{
    public static $key = 'diamonds';

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function delete(User $user, $model)
    {
        if ($user->hasPermissionTo('forceDelete ' . static::$key)) {
            return true;
        }

        if ($user->hasPermissionTo('manage own ' . static::$key) && $user->hasPermissionTo('forceDelete ' . static::$key)) {
            return $user->id == $model->user_id;
        }

        return false;
    }

}

