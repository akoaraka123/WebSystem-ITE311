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
                'email'    => 'admin@test.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'admin'
            ],
            [   
                'name'     => 'Teacher User',
                'email'    => 'teacher@test.com',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role'     => 'teacher'
            ],
            [
                'name'     => 'Student User',
                'email'    => 'student@test.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role'     => 'student'
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
