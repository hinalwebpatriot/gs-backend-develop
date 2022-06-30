<?php

namespace lenal\additional_content\Controllers;

use App\Http\Controllers\Controller;
use lenal\additional_content\Facades\AdditionalContent;

class AdditionalContentController extends Controller
{
    public function getMenuDropdown()
    {
        return AdditionalContent::menuDropdown();
    }

    public function faq()
    {
        return AdditionalContent::faq();
    }
}
