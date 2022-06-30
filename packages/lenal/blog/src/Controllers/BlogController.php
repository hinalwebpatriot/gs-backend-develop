<?php

namespace lenal\blog\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use lenal\blog\Facades\Blog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function list($slug = null)
    {
        return Blog::list($slug);
    }

    public function article($slug)
    {
        return Blog::article($slug);
    }

    public function categories()
    {
        return Blog::categories();
    }

    public function related($slug)
    {
        return Blog::related($slug);
    }

    public function topArticles()
    {
        return Blog::topArticles();
    }

}
