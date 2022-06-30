<?php


namespace App\Http\Controllers;


class SiteController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function generateException($hash)
    {
        if ($hash == md5(md5(date('Y-m-d')))) {
            throw new \Exception('My Test Exception');
        }
    }
}