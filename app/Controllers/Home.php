<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function angit()
    {
        // Load view named 'home_view.php'
        return view('angit');
    }

    public function index()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

        public function template()
    {
        return view('1');
    }
}
