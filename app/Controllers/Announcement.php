<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Announcement extends BaseController
{
    public function index()
    {
        // For now, static message (we’ll connect DB in Task 2)
        $data['announcements'] = [
            [
                'title' => 'Welcome to the Student Portal!',
                'content' => 'This is where announcements will appear.',
                'date' => date('Y-m-d H:i:s')
                
            ]
            
        ];

        return view('announcements', $data);
    }
}
