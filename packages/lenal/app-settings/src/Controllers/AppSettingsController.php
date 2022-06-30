<?php
/**
 * Created by PhpStorm.
 * User: nazarian
 * Date: 1/28/19
 * Time: 6:16 PM
 */

namespace lenal\AppSettings\Controllers;

use lenal\AppSettings\Facades\AppSettings;
use lenal\AppSettings\Requests\SelectedLocationsRequest;



class AppSettingsController
{
    public function locales()
    {
        return AppSettings::locales();
    }

    public function currencies()
    {
        return AppSettings::currencies();
    }

    public function locations()
    {
        return AppSettings::locations();
    }

    public function locationsData()
    {
        return AppSettings::locationsData();
    }


    public function locationsSelected(SelectedLocationsRequest $request)
    {
        return AppSettings::locationsSelected($request);
    }
}