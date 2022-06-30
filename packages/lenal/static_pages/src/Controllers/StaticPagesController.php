<?php

namespace lenal\static_pages\Controllers;

use App\Http\Controllers\Controller;

use lenal\static_pages\Facades\StaticPage;
use Illuminate\Http\Response;

class StaticPagesController extends Controller
{
    public function page($code)
    {
        return !empty($pageData = StaticPage::getPage($code))
            ? response()->json($pageData)
            : response()->json(null, Response::HTTP_NOT_FOUND);
    }



}
