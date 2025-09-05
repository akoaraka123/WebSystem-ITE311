<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Admin
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin'
            ],
            // Instructors
            [
                'name' => 'Instructor One',
                'email' => 'instructor1@example.com',
                'password' => password_hash('instr123', PASSWORD_DEFAULT),
                'role' => 'instructor'
            ],
            [
                'name' => 'Instructor Two',
                'email' => 'instructor2@example.com',
                'password' => password_hash('instr123', PASSWORD_DEFAULT),
                'role' => 'instructor'
            ],
            // Students
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student'
            ],
            [
                'name' => 'Maria Clara',
                'email' => 'maria@example.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'student'
            ]
        ];

        // Insert sa 'users' table
        $this->db->table('users')->insertBatch($data);
    }
}
