<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    public function index()
    {
        $model = new AnnouncementModel();

        // ✅ Fetch all announcements ordered by date (newest first)
        $data['announcements'] = $model->orderBy('created_at', 'DESC')->findAll();

        // ✅ Debug (optional, remove after test)
        // echo '<pre>'; print_r($data['announcements']); die;

        return view('announcements', $data);
    }
}
