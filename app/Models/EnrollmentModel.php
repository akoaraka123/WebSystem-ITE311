<?php namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'enrollment_date', 'status', 'teacher_approved', 'admin_approved', 'teacher_approved_at', 'admin_approved_at'];
    protected $useTimestamps = true;
    protected $createdField = 'enrollment_date';
    protected $updatedField = '';

    // Get all courses a student is enrolled in (only teacher approved)
    public function getUserEnrollments($userID)
    {
        return $this->select('enrollments.id, 
                            courses.id as course_id, 
                            courses.title, 
                            courses.description, 
                            courses.course_number,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_time,
                            courses.schedule_date,
                            courses.duration,
                            enrollments.enrollment_date, 
                            enrollments.status, 
                            enrollments.teacher_approved,
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->where('enrollments.user_id', $userID)
                    ->where('enrollments.teacher_approved', 1)
                    ->where('enrollments.status', 'accepted')
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Get pending enrollment requests for a student
    public function getPendingEnrollments($userID)
    {
        return $this->select('enrollments.id, courses.id as course_id, courses.title, courses.description, courses.teacher_id, enrollments.enrollment_date, enrollments.status, enrollments.admin_approved, users.name as teacher_name')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
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

    // Check if user is already enrolled in a course (fully approved, pending, or partially approved)
    public function isAlreadyEnrolled($userID, $courseID)
    {
        return $this->where('user_id', $userID)
                   ->where('course_id', $courseID)
                   ->whereIn('status', ['accepted', 'pending'])
                   ->countAllResults() > 0;
    }

    // Get student's enrolled program IDs (for program restriction check)
    public function getStudentProgramIds($userID)
    {
        return $this->distinct(true)
                   ->select('courses.program_id')
                   ->join('courses', 'courses.id = enrollments.course_id')
                   ->where('enrollments.user_id', $userID)
                   ->whereIn('enrollments.status', ['accepted', 'pending'])
                   ->where('courses.program_id IS NOT NULL')
                   ->findAll();
    }

    // Check if enrollment is fully approved (teacher approved)
    public function isFullyApproved($enrollmentId)
    {
        $enrollment = $this->find($enrollmentId);
        if (!$enrollment) {
            return false;
        }
        return ($enrollment['teacher_approved'] == 1 && $enrollment['status'] == 'accepted');
    }

    // Approve enrollment by teacher (sets status to accepted immediately)
    public function approveByTeacher($enrollmentId, $courseId, $teacherId)
    {
        // Verify the course belongs to this teacher
        $course = $this->db->table('courses')->where('id', $courseId)->where('teacher_id', $teacherId)->get()->getRowArray();
        if (!$course) {
            return false;
        }

        $updated = $this->where('id', $enrollmentId)
                        ->where('course_id', $courseId)
                        ->set([
                            'teacher_approved' => 1,
                            'teacher_approved_at' => date('Y-m-d H:i:s'),
                            'status' => 'accepted' // Immediately accepted when teacher approves
                        ])
                        ->update();

        return $updated;
    }

    // Approve enrollment by admin (optional - for admin direct enrollment)
    public function approveByAdmin($enrollmentId)
    {
        $updated = $this->where('id', $enrollmentId)
                        ->set([
                            'admin_approved' => 1,
                            'admin_approved_at' => date('Y-m-d H:i:s'),
                            'teacher_approved' => 1, // Admin can bypass teacher approval
                            'teacher_approved_at' => date('Y-m-d H:i:s'),
                            'status' => 'accepted'
                        ])
                        ->update();

        return $updated;
    }

    // Reject enrollment by teacher
    public function rejectByTeacher($enrollmentId, $courseId, $teacherId)
    {
        // Verify the course belongs to this teacher
        $course = $this->db->table('courses')->where('id', $courseId)->where('teacher_id', $teacherId)->get()->getRowArray();
        if (!$course) {
            return false;
        }

        return $this->where('id', $enrollmentId)
                    ->where('course_id', $courseId)
                    ->set('status', 'rejected')
                    ->update();
    }

    // Reject enrollment by admin
    public function rejectByAdmin($enrollmentId)
    {
        return $this->where('id', $enrollmentId)
                    ->set('status', 'rejected')
                    ->update();
    }

    // Accept an enrollment request (for student accepting enrollment request from admin/teacher)
    public function acceptEnrollment($enrollmentId, $userID)
    {
        // Verify the enrollment belongs to this user and is pending
        $enrollment = $this->where('id', $enrollmentId)
                          ->where('user_id', $userID)
                          ->where('status', 'pending')
                          ->first();
        
        if (!$enrollment) {
            return false;
        }
        
        // Update status to accepted
        // If admin approved it, also set teacher_approved (admin can bypass teacher approval)
        $updateData = [
            'status' => 'accepted'
        ];
        
        // If admin already approved, also mark teacher as approved (admin bypass)
        if ($enrollment['admin_approved'] == 1) {
            $updateData['teacher_approved'] = 1;
            $updateData['teacher_approved_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->where('id', $enrollmentId)
                   ->where('user_id', $userID)
                   ->set($updateData)
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
        return $this->select('enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.status, enrollments.enrollment_date, enrollments.teacher_approved, enrollments.admin_approved, enrollments.teacher_approved_at, enrollments.admin_approved_at, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Get pending enrollments waiting for teacher approval
    public function getPendingTeacherApprovals($courseId)
    {
        return $this->select('enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.status, enrollments.enrollment_date, enrollments.teacher_approved, enrollments.admin_approved, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->where('enrollments.status', 'pending')
                    ->where('enrollments.teacher_approved', 0)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Get pending enrollments waiting for admin approval (for admin dashboard - optional)
    public function getPendingAdminApprovals()
    {
        // This is now optional - admin can see all pending enrollments if needed
        return $this->select('enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.status, enrollments.enrollment_date, enrollments.teacher_approved, enrollments.admin_approved, enrollments.teacher_approved_at, courses.title as course_title, courses.teacher_id, users.name as student_name, users.email as student_email, teachers.name as teacher_name')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->join('users as teachers', 'teachers.id = courses.teacher_id')
                    ->where('enrollments.status', 'pending')
                    ->where('enrollments.teacher_approved', 0)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }

    // Get all pending enrollments for a teacher (across all their courses)
    public function getPendingEnrollmentsForTeacher($teacherId)
    {
        return $this->select('enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.status, enrollments.enrollment_date, enrollments.teacher_approved, courses.title as course_title, courses.teacher_id, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->join('courses', 'courses.id = enrollments.course_id')
                    ->where('courses.teacher_id', $teacherId)
                    ->where('enrollments.status', 'pending')
                    ->where('enrollments.teacher_approved', 0)
                    ->orderBy('enrollments.enrollment_date', 'DESC')
                    ->findAll();
    }
}
