<?php

namespace App\Policies;

use Eminiarts\NovaPermissions\Policies\Policy;

class StaticBlockPolicy extends Policy
{
    public static $key = 'static_blocks';
}