<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses'; // ✅ dapat plural
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'teacher_id', 'program_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get courses taught by a specific teacher
    public function getTeacherCourses($teacherId)
    {
        return $this->where('teacher_id', $teacherId)->findAll();
    }

    // Get available courses for students (courses they're not enrolled in)
    public function getAvailableCourses($enrolledIds = [])
    {
        if (empty($enrolledIds)) {
            return $this->findAll();
        }
        return $this->whereNotIn('id', $enrolledIds)->findAll();
    }

    // Count all courses
    public function countAll()
    {
        return $this->countAllResults();
    }
}
