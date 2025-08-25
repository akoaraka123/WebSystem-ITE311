<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Load view named 'home_view.php'
        return view('home_view');
    }
}
