<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'role'     => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
            ],
            [
                'name'     => 'Instructor User',
                'email'    => 'instructor@example.com',
                'role'     => 'instructor',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
            ],
            [
                'name'     => 'Student User',
                'email'    => 'student@example.com',
                'role'     => 'student',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
            ],
        ];

        // Insert batch
        $this->db->table('users')->insertBatch($data);
    }
}
