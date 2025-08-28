<?php

namespace App\Controllers;

class Template extends BaseController
{
    public function index()
    {
        return view('/1');
    }

    public function page2()
    {
        return view('/2');
    }

    public function page3()
    {
        return view('/3');
    }
}
