<?php

namespace lenal\additional_content\Helpers;

use lenal\additional_content\Models\FAQ;
use lenal\additional_content\Models\MenuDropdownContent;
use lenal\additional_content\Resources\FAQResource;
use lenal\additional_content\Resources\MenuDropdownCollection;

class AdditionalContent
{
    public function menuDropdown()
    {
        $locale = app()->getLocale();

        return new MenuDropdownCollection(MenuDropdownContent::where(['locale' => $locale])->get());
    }

    public function faq()
    {
        return FAQResource::collection(FAQ::all());
    }

}
