<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];

    // Get all courses a student is enrolled in
    public function getUserEnrollments($userID)
    {
        return $this->select('courses.id, courses.title, courses.description')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $userID)
                    ->findAll();
    }

    // Check if user already enrolled
    public function isAlreadyEnrolled($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                    ->where('course_id', $courseID)
                    ->countAllResults() > 0;
    }

    // ✅ Unenroll a user from a course
    public function unenroll($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                    ->where('course_id', $courseID)
                    ->delete();
    }
}
