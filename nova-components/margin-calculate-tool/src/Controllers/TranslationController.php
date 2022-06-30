<?php

namespace lenal\MarginCalculateTool\Controllers;

use App\Http\Controllers\Controller;


/**
 * Class MarginCalculateToolController
 *
 * @package lenal\blog\Controllers
 */
class TranslationController extends Controller
{
    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    public function index()
    {
        return trans('MarginCalculateTool::api');
    }
}
