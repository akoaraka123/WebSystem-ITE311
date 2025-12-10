<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AcademicDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Academic Years
        $academicYears = [
            [
                'year_start' => 2024,
                'year_end' => 2025,
                'display_name' => '2024-2025',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'year_start' => 2025,
                'year_end' => 2026,
                'display_name' => '2025-2026',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $acadYearIds = [];
        foreach ($academicYears as $acadYear) {
            // Check if academic year already exists
            $existing = $this->db->table('academic_years')
                ->where('year_start', $acadYear['year_start'])
                ->where('year_end', $acadYear['year_end'])
                ->countAllResults();
            
            if ($existing == 0) {
                $this->db->table('academic_years')->insert($acadYear);
                $acadYearId = $this->db->insertID();
                echo "Added Academic Year: {$acadYear['display_name']} ✅\n";
            } else {
                // Get existing ID
                $existingAcadYear = $this->db->table('academic_years')
                    ->where('year_start', $acadYear['year_start'])
                    ->where('year_end', $acadYear['year_end'])
                    ->get()
                    ->getRowArray();
                $acadYearId = $existingAcadYear['id'];
                echo "Academic Year {$acadYear['display_name']} already exists\n";
            }
            
            $acadYearIds[] = $acadYearId;
        }

        // 2. Create Semesters for each Academic Year
        $semesters = [
            [
                'semester_number' => 1,
                'name' => '1st Semester',
                'start_date' => '2024-08-01',
                'end_date' => '2024-12-15',
            ],
            [
                'semester_number' => 2,
                'name' => '2nd Semester',
                'start_date' => '2025-01-15',
                'end_date' => '2025-05-30',
            ],
        ];

        $semesterIds = [];
        foreach ($acadYearIds as $acadYearId) {
            foreach ($semesters as $semester) {
                // Check if semester already exists for this academic year
                $existing = $this->db->table('semesters')
                    ->where('acad_year_id', $acadYearId)
                    ->where('semester_number', $semester['semester_number'])
                    ->countAllResults();
                
                if ($existing == 0) {
                    $semesterData = [
                        'acad_year_id' => $acadYearId,
                        'semester_number' => $semester['semester_number'],
                        'name' => $semester['name'],
                        'start_date' => $semester['start_date'],
                        'end_date' => $semester['end_date'],
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    
                    $this->db->table('semesters')->insert($semesterData);
                    $semesterId = $this->db->insertID();
                    echo "  Added Semester: {$semester['name']} ✅\n";
                } else {
                    // Get existing ID
                    $existingSemester = $this->db->table('semesters')
                        ->where('acad_year_id', $acadYearId)
                        ->where('semester_number', $semester['semester_number'])
                        ->get()
                        ->getRowArray();
                    $semesterId = $existingSemester['id'];
                    echo "  Semester {$semester['name']} already exists\n";
                }
                
                $semesterIds[] = $semesterId;
            }
        }

        // 3. Create Terms for each Semester
        $terms = [
            [
                'term_name' => 'Prelim',
                'term_order' => 1,
            ],
            [
                'term_name' => 'Midterm',
                'term_order' => 2,
            ],
            [
                'term_name' => 'Finals',
                'term_order' => 3,
            ],
        ];

        foreach ($semesterIds as $semesterId) {
            foreach ($terms as $term) {
                // Check if term already exists for this semester
                $existing = $this->db->table('terms')
                    ->where('semester_id', $semesterId)
                    ->where('term_name', $term['term_name'])
                    ->countAllResults();
                
                if ($existing == 0) {
                    $termData = [
                        'semester_id' => $semesterId,
                        'term_name' => $term['term_name'],
                        'term_order' => $term['term_order'],
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    
                    $this->db->table('terms')->insert($termData);
                    echo "    Added Term: {$term['term_name']} ✅\n";
                } else {
                    echo "    Term {$term['term_name']} already exists\n";
                }
            }
        }

        echo "\n✅ Academic data seeding completed!\n";
        echo "   - Academic Years: " . count($acadYearIds) . "\n";
        echo "   - Semesters: " . count($semesterIds) . "\n";
        echo "   - Terms: " . (count($semesterIds) * count($terms)) . "\n";
    }
}

