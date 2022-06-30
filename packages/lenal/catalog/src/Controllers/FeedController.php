<?php

namespace lenal\catalog\Controllers;


use App\Http\Controllers\Controller;

class FeedController extends Controller
{
    public function download()
    {
        $feedPath = public_path('feed/google.csv');

        return response()->download($feedPath, basename($feedPath), [
            'Content-Type' => 'text/csv',
        ]);
    }
}
