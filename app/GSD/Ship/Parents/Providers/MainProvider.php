<?php

namespace GSD\Ship\Parents\Providers;

use GSD\Core\Abstracts\Providers\MainProvider as CoreMainProvider;

/**
 * Class MainProvider.
 *
 * @author  Mahmoud Zalt <mahmoud@zalt.me>
 */
abstract class MainProvider extends CoreMainProvider
{

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register anything in the container.
     */
    public function register()
    {
        parent::register();
    }

}
