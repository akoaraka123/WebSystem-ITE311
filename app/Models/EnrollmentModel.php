<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date'];
    protected $useTimestamps = true;
    protected $createdField = 'enrollment_date';
    protected $updatedField = '';

    // Get all courses a student is enrolled in
    public function getUserEnrollments($userID)
    {
        return $this->select('enrollments.id, courses.id as course_id, courses.title, courses.description, enrollments.enrollment_date')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $userID)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Alias for getUserEnrollments - used by Course controller
    public function getEnrolledCourses($userID)
    {
        return $this->getUserEnrollments($userID);
    }

    // Check if user is already enrolled in a course
    public function isAlreadyEnrolled($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                   ->where('course_id', $courseID)
                   ->countAllResults() > 0;
    }

    // Unenroll a user from a course
    public function unenroll($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                    ->where('course_id', $courseID)
                    ->delete();
    }

    // ✅ Explicit insert helper used by Course::enroll
    public function enrollUser(array $data)
    {
        return $this->insert($data);
    }
}
