<?php

namespace lenal\social\Controllers;

use App\Http\Controllers\Controller;
use lenal\social\Facades\Social;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SocialController extends Controller
{
    public function getSupportContacts()
    {
        return Social::getSupportContacts();
    }
}
