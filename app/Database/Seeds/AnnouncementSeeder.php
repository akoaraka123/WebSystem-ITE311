<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Welcome to the Student Portal',
                'content' => 'We are excited to have you! Stay updated for future announcements.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Midterm Exam Schedule',
                'content' => 'The midterm exams will start next week. Please check your schedule in your account.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('announcements')->insertBatch($data);
    }
}
