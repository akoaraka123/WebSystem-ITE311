<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status'];
    protected $useTimestamps = true;
    protected $createdField = 'enrollment_date';
    protected $updatedField = '';

    // Get all courses a student is enrolled in (only accepted)
    // Note: NULL status is treated as 'accepted' for backward compatibility
    public function getUserEnrollments($userID)
    {
        return $this->select('enrollments.id, courses.id as course_id, courses.title, courses.description, enrollments.enrollment_date, enrollments.status')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('enrollments.user_id', $userID)
                    ->groupStart()
                        ->where('enrollments.status', 'accepted')
                        ->orWhere('enrollments.status IS NULL')
                    ->groupEnd()
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Get pending enrollment requests for a student
    public function getPendingEnrollments($userID)
    {
        return $this->select('enrollments.id, courses.id as course_id, courses.title, courses.description, courses.teacher_id, enrollments.enrollment_date, enrollments.status, users.name as teacher_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users', 'users.id = courses.teacher_id')
                    ->where('enrollments.user_id', $userID)
                    ->where('enrollments.status', 'pending')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Alias for getUserEnrollments - used by Course controller
    public function getEnrolledCourses($userID)
    {
        return $this->getUserEnrollments($userID);
    }

    // Check if user is already enrolled in a course (accepted or pending)
    // Note: NULL status is treated as 'accepted' for backward compatibility
    public function isAlreadyEnrolled($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                   ->where('course_id', $courseID)
                   ->groupStart()
                       ->whereIn('status', ['accepted', 'pending'])
                       ->orWhere('status IS NULL')
                   ->groupEnd()
                   ->countAllResults() > 0;
    }

    // Accept an enrollment request
    public function acceptEnrollment($enrollmentId, $userID)
    {
        return $this->where('id', $enrollmentId)
                    ->where('user_id', $userID)
                    ->where('status', 'pending')
                    ->set('status', 'accepted')
                    ->update();
    }

    // Reject an enrollment request
    public function rejectEnrollment($enrollmentId, $userID)
    {
        return $this->where('id', $enrollmentId)
                    ->where('user_id', $userID)
                    ->where('status', 'pending')
                    ->set('status', 'rejected')
                    ->update();
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

    // Get all enrollments for a course with student details and status
    public function getCourseEnrollments($courseId)
    {
        return $this->select('enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.status, enrollments.enrollment_date, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
}
