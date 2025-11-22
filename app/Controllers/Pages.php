<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        return view('pages/home');
    }

    public function home()
    {
        return view('pages/home');
    }

    public function about()
    {
        return view('pages/about');
    }

    public function contact()
    {
        return view('pages/contact');
    }
}
