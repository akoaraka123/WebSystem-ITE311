<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentProgramModel extends Model
{
    protected $table = 'student_programs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'program_id', 'acad_year_id', 'enrollment_date', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get student's active program enrollment
     */
    public function getStudentProgram($userId, $acadYearId = null)
    {
        $builder = $this->where('user_id', $userId)
                      ->where('status', 'active');
        
        if ($acadYearId) {
            $builder->where('acad_year_id', $acadYearId);
        }
        
        return $builder->orderBy('enrollment_date', 'DESC')
                     ->first();
    }

    /**
     * Get all program enrollments for a student
     */
    public function getStudentPrograms($userId)
    {
        return $this->select('student_programs.*, programs.code as program_code, programs.name as program_name, academic_years.display_name as acad_year_name')
                   ->join('programs', 'programs.id = student_programs.program_id')
                   ->join('academic_years', 'academic_years.id = student_programs.acad_year_id', 'left')
                   ->where('student_programs.user_id', $userId)
                   ->orderBy('student_programs.enrollment_date', 'DESC')
                   ->findAll();
    }

    /**
     * Check if student is enrolled in a program
     */
    public function isEnrolledInProgram($userId, $programId, $acadYearId = null)
    {
        $builder = $this->where('user_id', $userId)
                       ->where('program_id', $programId)
                       ->where('status', 'active');
        
        if ($acadYearId) {
            $builder->where('acad_year_id', $acadYearId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Enroll student in a program
     */
    public function enrollStudent($userId, $programId, $acadYearId = null)
    {
        // Check if already enrolled
        if ($this->isEnrolledInProgram($userId, $programId, $acadYearId)) {
            return false; // Already enrolled
        }

        $data = [
            'user_id' => $userId,
            'program_id' => $programId,
            'acad_year_id' => $acadYearId,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        return $this->insert($data);
    }

    /**
     * Get students enrolled in a program
     */
    public function getProgramStudents($programId, $acadYearId = null)
    {
        $builder = $this->select('student_programs.*, users.name as student_name, users.email as student_email, programs.code as program_code, programs.name as program_name, academic_years.display_name as acad_year_name')
                       ->join('users', 'users.id = student_programs.user_id')
                       ->join('programs', 'programs.id = student_programs.program_id')
                       ->join('academic_years', 'academic_years.id = student_programs.acad_year_id', 'left')
                       ->where('student_programs.program_id', $programId)
                       ->where('student_programs.status', 'active');
        
        if ($acadYearId) {
            $builder->where('student_programs.acad_year_id', $acadYearId);
        }
        
        return $builder->orderBy('users.name', 'ASC')
                      ->findAll();
    }

    /**
     * Get all enrolled students grouped by program
     */
    public function getAllEnrolledStudentsByProgram()
    {
        $enrollments = $this->select('student_programs.*, users.name as student_name, users.email as student_email, users.id as user_id, programs.id as program_id, programs.code as program_code, programs.name as program_name, academic_years.display_name as acad_year_name')
                           ->join('users', 'users.id = student_programs.user_id')
                           ->join('programs', 'programs.id = student_programs.program_id')
                           ->join('academic_years', 'academic_years.id = student_programs.acad_year_id', 'left')
                           ->where('student_programs.status', 'active')
                           ->orderBy('programs.code', 'ASC')
                           ->orderBy('users.name', 'ASC')
                           ->findAll();

        // Group by program
        $grouped = [];
        foreach ($enrollments as $enrollment) {
            $programId = $enrollment['program_id'];
            $programKey = $programId;
            
            if (!isset($grouped[$programKey])) {
                $grouped[$programKey] = [
                    'program_id' => $programId,
                    'program_code' => $enrollment['program_code'],
                    'program_name' => $enrollment['program_name'],
                    'students' => []
                ];
            }
            
            $grouped[$programKey]['students'][] = [
                'user_id' => $enrollment['user_id'],
                'student_name' => $enrollment['student_name'],
                'student_email' => $enrollment['student_email'],
                'enrollment_date' => $enrollment['enrollment_date'],
                'acad_year_name' => $enrollment['acad_year_name'] ?? 'N/A'
            ];
        }
        
        return $grouped;
    }
}

