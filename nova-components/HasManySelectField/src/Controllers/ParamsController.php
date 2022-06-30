<?php

namespace HasManySelectField\Controllers;


use App\Http\Controllers\Controller;

class ParamsController extends Controller
{
    public function index()
    {
        return response()->json([
            'apiBaseUrl' => url('nova-vendor/has-many-select')
        ]);
    }
}