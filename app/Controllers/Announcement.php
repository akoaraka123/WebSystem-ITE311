<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Announcement extends Controller
{
    public function index()
    {
        // Load the model
        $announcementModel = new AnnouncementModel();

        // Fetch all announcements (later gagamitin sa Task 2)
        $data['announcements'] = $announcementModel->orderBy('created_at', 'DESC')->findAll();

        // Pass data to the view
        return view('announcements', $data);
    }
}
