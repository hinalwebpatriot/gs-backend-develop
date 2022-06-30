<?php

namespace lenal\social\Helpers;

use lenal\social\Models\ContactLink;
use lenal\social\Resources\SupportContactResource;

class SocialHelper
{
    public function getSupportContacts()
    {
        return SupportContactResource::collection(ContactLink::whereNotNull('value')->get());
    }
}
