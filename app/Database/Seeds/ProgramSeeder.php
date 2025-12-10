<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'code'        => 'BSIT',
                'name'        => 'Bachelor of Science in Information Technology',
                'description' => 'A program that focuses on the study of information systems, software development, and computer applications.',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'BSCS',
                'name'        => 'Bachelor of Science in Computer Science',
                'description' => 'A program that emphasizes the theoretical foundations of computing and software development.',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'BSBA',
                'name'        => 'Bachelor of Science in Business Administration',
                'description' => 'A program that provides students with knowledge in business management, marketing, and administration.',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'BSE',
                'name'        => 'Bachelor of Science in Education',
                'description' => 'A program designed to prepare students for careers in teaching and educational leadership.',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'code'        => 'BSN',
                'name'        => 'Bachelor of Science in Nursing',
                'description' => 'A program that prepares students for professional nursing practice and healthcare services.',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        // Check each program individually and insert only if it doesn't exist
        $inserted = 0;
        $skipped = 0;
        
        foreach ($data as $program) {
            // Check if program with this code already exists
            $existing = $this->db->table('programs')
                ->where('code', $program['code'])
                ->countAllResults();
            
            if ($existing == 0) {
                // Insert this program
                $this->db->table('programs')->insert($program);
                $inserted++;
                echo "Added program: {$program['code']} - {$program['name']} ✅\n";
            } else {
                $skipped++;
                echo "Skipped program: {$program['code']} (already exists)\n";
            }
        }
        
        if ($inserted > 0) {
            echo "\nSuccessfully seeded {$inserted} program(s). {$skipped} program(s) already existed.\n";
        } else {
            echo "\nAll programs already exist. No new programs were added.\n";
        }
    }
}

