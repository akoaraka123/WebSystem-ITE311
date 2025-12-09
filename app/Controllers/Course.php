<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;

class Course extends BaseController
{
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function index()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = $session->get('role');
        
        if ($role === 'admin') {
            // Admin sees all courses
            $courses = $this->courseModel->findAll();
        } elseif ($role === 'student') {
            // Student sees available courses (not enrolled)
            $enrolled = $this->enrollmentModel->getEnrolledCourses($session->get('userID'));
            $enrolledIds = array_column($enrolled, 'course_id');
            $courses = $this->courseModel->getAvailableCourses($enrolledIds);
        } else {
            // Teachers see their own courses
            $courses = $this->courseModel->getTeacherCourses($session->get('userID'));
        }

        $data = [
            'title' => 'Courses - LMS',
            'courses' => $courses,
            'user' => $session->get(),
            'searchTerm' => ''
        ];

        return view('courses/index', $data);
    }

    public function myCourses()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $role = $session->get('role');
        
        $enrollmentCounts = [];

        if ($role === 'student') {
            $courses = $this->enrollmentModel->getEnrolledCourses($session->get('userID'));
        } elseif ($role === 'teacher') {
            $courses = $this->courseModel->getTeacherCourses($session->get('userID'));
            if (!empty($courses)) {
                $courseIds = array_filter(array_map('intval', array_column($courses, 'id')));
                if (!empty($courseIds)) {
                    $counts = $this->enrollmentModel
                        ->select('course_id, COUNT(*) as total')
                        ->whereIn('course_id', $courseIds)
                        ->groupBy('course_id')
                        ->findAll();

                    foreach ($counts as $row) {
                        $courseId = (int) ($row['course_id'] ?? 0);
                        if ($courseId > 0) {
                            $enrollmentCounts[$courseId] = (int) ($row['total'] ?? 0);
                        }
                    }
                }
            }
        } else {
            $courses = [];
        }

        $data = [
            'title' => 'My Courses - LMS',
            'courses' => $courses,
            'enrollmentCounts' => $enrollmentCounts,
            'user' => $session->get()
        ];

        return view('courses/my-courses', $data);
    }

    public function create()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Create Course - LMS',
            'user' => $session->get()
        ];

        return view('courses/create', $data);
    }

    public function store()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return redirect()->to(base_url('dashboard'));
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[200]',
            'description' => 'required|min_length[10]'
        ];

        if ($this->validate($rules)) {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'teacher_id' => $session->get('userID'),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->courseModel->insert($data);
            $session->setFlashdata('success', 'Course created successfully!');
            return redirect()->to(base_url('my-courses'));
        } else {
            $session->setFlashdata('error', 'Please correct the errors below.');
            return redirect()->to(base_url('create-course'))->withInput();
        }
    }

    public function edit($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $course = $this->courseModel->find($id);
        
        if (!$course || ($session->get('role') === 'teacher' && $course['teacher_id'] != $session->get('userID'))) {
            $session->setFlashdata('error', 'Course not found or access denied.');
            return redirect()->to(base_url('my-courses'));
        }

        $data = [
            'title' => 'Edit Course - LMS',
            'course' => $course,
            'user' => $session->get()
        ];

        return view('courses/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $course = $this->courseModel->find($id);
        
        if (!$course || ($session->get('role') === 'teacher' && $course['teacher_id'] != $session->get('userID'))) {
            $session->setFlashdata('error', 'Course not found or access denied.');
            return redirect()->to(base_url('my-courses'));
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[200]',
            'description' => 'required|min_length[10]'
        ];

        if ($this->validate($rules)) {
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->courseModel->update($id, $data);
            $session->setFlashdata('success', 'Course updated successfully!');
            return redirect()->to(base_url('my-courses'));
        } else {
            $session->setFlashdata('error', 'Please correct the errors below.');
            return redirect()->to(base_url('edit-course/' . $id))->withInput();
        }
    }

    public function view($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $course = $this->courseModel->find($id);
        
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('courses'));
        }

        $materialModel = new MaterialModel();
        $materials = $materialModel->getMaterialsByCourse($course['id']);

        $data = [
            'title' => $course['title'] . ' - LMS',
            'course' => $course,
            'materials' => $materials,
            'user' => $session->get()
        ];

        return view('courses/view', $data);
    }

    public function unenroll($courseID)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userID = (int) $session->get('userID');
        $courseID = (int) $courseID;
        if ($courseID <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course.', 'csrf_hash' => csrf_hash()]);
        }

        $course = $this->courseModel->find($courseID);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found.', 'csrf_hash' => csrf_hash()]);
        }

        $removed = $this->enrollmentModel->unenroll($userID, $courseID);

        // Clear related notifications for both student and teacher
        $notif = new \App\Models\NotificationModel();
        $notif->clearEnrollmentNotifs($userID, (int)($course['teacher_id'] ?? 0), (string)($course['title'] ?? ''));

        return $this->response->setJSON([
            'success' => (bool) $removed,
            'message' => $removed ? 'Unenrolled and notifications cleared.' : 'No enrollment to remove.',
            'csrf_hash' => csrf_hash(),
        ]);
    }
    
    public function enroll()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userID = $session->get('userID');
        $courseID = (int) $this->request->getPost('course_id');

        if ($courseID <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course.', 'csrf_hash' => csrf_hash()]);
        }

        $course = $this->courseModel->find($courseID);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found.', 'csrf_hash' => csrf_hash()]);
        }

        if ($this->enrollmentModel->isAlreadyEnrolled($userID, $courseID)) {
            return $this->response->setJSON(['success' => false, 'message' => 'You are already enrolled in this course.', 'csrf_hash' => csrf_hash()]);
        }

        $this->enrollmentModel->enrollUser([
            'user_id' => $userID,
            'course_id' => $courseID,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);

        // Create notifications
        $notif = new \App\Models\NotificationModel();
        // Notify the student
        $notif->add($userID, 'You enrolled in: ' . ($course['title'] ?? 'a course'));
        // Notify the course teacher
        if (!empty($course['teacher_id'])) {
            $notif->add((int)$course['teacher_id'], 'A student enrolled in your course: ' . ($course['title'] ?? ''));
        }

        return $this->response->setJSON(['success' => true, 'message' => 'You have successfully enrolled in the course!', 'csrf_hash' => csrf_hash()]);
    }

    public function delete($id = null)
    {
        $session = session();
        
        // Security: only teachers can delete courses
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            $session->setFlashdata('error', 'Unauthorized to delete courses.');
            return redirect()->to(base_url('dashboard'));
        }

        // Validate course ID
        if (!$id || !is_numeric($id)) {
            $session->setFlashdata('error', 'Invalid course ID.');
            return redirect()->to(base_url('dashboard'));
        }

        $userID = $session->get('userID');
        
        // Check if course exists and belongs to this teacher
        $course = $this->courseModel->find($id);
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('dashboard'));
        }

        if ($course['teacher_id'] != $userID) {
            $session->setFlashdata('error', 'You can only delete your own courses.');
            return redirect()->to(base_url('dashboard'));
        }

        try {
            // Delete all enrollments for this course
            $this->enrollmentModel->where('course_id', $id)->delete();
            
            // Delete all materials for this course
            $materialModel = new \App\Models\MaterialModel();
            $materialModel->where('course_id', $id)->delete();
            
            // Delete the course
            $this->courseModel->delete($id);
            
            // Create notification for the teacher
            $notif = new \App\Models\NotificationModel();
            $notif->add($userID, 'You deleted your course: ' . ($course['title'] ?? 'Untitled Course'));
            
            $session->setFlashdata('success', 'Course and all related data deleted successfully.');
            
        } catch (\Exception $e) {
            $session->setFlashdata('error', 'Failed to delete course: ' . $e->getMessage());
        }

        return redirect()->to(base_url('dashboard'));
    }

    public function search()
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)
                    ->setJSON(['message' => 'Unauthorized']);
            }

            return redirect()->to(base_url('login'));
        }

        $searchTerm = trim(
            $this->request->getGet('search_term')
                ?? $this->request->getPost('search_term')
                ?? ''
        );

        $role = $session->get('role');
        $courseModel = new CourseModel();

        if ($role === 'student') {
            $enrolled = $this->enrollmentModel->getEnrolledCourses($session->get('userID'));
            $enrolledIds = array_filter(array_map('intval', array_column($enrolled, 'course_id')));
            if (!empty($enrolledIds)) {
                $courseModel->whereNotIn('id', $enrolledIds);
            }
        } elseif ($role === 'teacher') {
            $courseModel->where('teacher_id', $session->get('userID'));
        }

        if ($searchTerm !== '') {
            $courseModel->groupStart()
                ->like('title', $searchTerm)
                ->orLike('description', $searchTerm)
                ->groupEnd();
        }

        $courses = $courseModel->findAll();

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($courses);
        }

        return view('courses/index', [
            'title' => 'Courses - LMS',
            'courses' => $courses,
            'searchTerm' => $searchTerm,
            'user' => $session->get()
        ]);
    }

    /**
     * Get available students for a course (students not yet enrolled)
     */
    public function getAvailableStudents($courseId)
    {
        $session = session();
        
        // Security: only teachers can view available students
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $courseId;
        if ($courseId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists and belongs to this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        if ($course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        // Get all students (soft-deleted students are automatically excluded)
        $userModel = new \App\Models\UserModel();
        $allStudents = $userModel->where('role', 'student')
                                ->select('id, name, email')
                                ->findAll();

        // Get enrolled student IDs for this course (only accepted and pending, NOT rejected)
        // This allows teachers to re-add students who previously rejected
        $enrolledStudentIds = $this->enrollmentModel->where('course_id', $courseId)
                                                     ->groupStart()
                                                         ->whereIn('status', ['accepted', 'pending'])
                                                         ->orWhere('status IS NULL')
                                                     ->groupEnd()
                                                     ->select('user_id')
                                                     ->findAll();
        $enrolledIds = array_column($enrolledStudentIds, 'user_id');

        // Filter out enrolled students (only those with accepted/pending status)
        $availableStudents = array_filter($allStudents, function($student) use ($enrolledIds) {
            return !in_array($student['id'], $enrolledIds);
        });

        // Re-index array
        $availableStudents = array_values($availableStudents);

        return $this->response->setJSON([
            'success' => true,
            'students' => $availableStudents,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Add a student to a course
     */
    public function addStudent()
    {
        $session = session();
        
        // Security: only teachers can add students
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        $studentId = (int) $this->request->getPost('student_id');

        if ($courseId <= 0 || $studentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course or student ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists and belongs to this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        if ($course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        // Verify student exists and is actually a student
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Student not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if already enrolled (accepted or pending)
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Student is already enrolled or has a pending request for this course', 
                'csrf_hash' => csrf_hash()
            ]);
        }

        // Check if there's a rejected enrollment - if so, update it to pending instead of creating new
        $rejectedEnrollment = $this->enrollmentModel->where('user_id', $studentId)
                                                     ->where('course_id', $courseId)
                                                     ->where('status', 'rejected')
                                                     ->first();

        // Create pending enrollment request
        try {
            if ($rejectedEnrollment) {
                // Update rejected enrollment to pending (allows teacher to re-add after student rejected)
                $this->enrollmentModel->update($rejectedEnrollment['id'], [
                    'status' => 'pending',
                    'enrollment_date' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Create new pending enrollment
                $this->enrollmentModel->enrollUser([
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'enrollment_date' => date('Y-m-d H:i:s'),
                    'status' => 'pending'
                ]);
            }

            // Create notifications
            $notif = new \App\Models\NotificationModel();
            // Notify the student about the enrollment request
            $notif->add($studentId, 'You have a new enrollment request for: ' . ($course['title'] ?? 'a course'));

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request sent to student. They need to accept it.',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to add student: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Accept an enrollment request
     */
    public function acceptEnrollment()
    {
        $session = session();
        
        // Security: only students can accept enrollment requests
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $userID = $session->get('userID');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $updated = $this->enrollmentModel->acceptEnrollment($enrollmentId, $userID);
            
            if ($updated) {
                // Get course info for notification
                $enrollment = $this->enrollmentModel->find($enrollmentId);
                if ($enrollment) {
                    $course = $this->courseModel->find($enrollment['course_id']);
                    if ($course) {
                        // Notify the teacher
                        $notif = new \App\Models\NotificationModel();
                        $notif->add((int)$course['teacher_id'], 'A student accepted enrollment in your course: ' . ($course['title'] ?? ''));
                    }
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment accepted successfully',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(404)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Enrollment request not found or already processed',
                        'csrf_hash' => csrf_hash()
                    ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to accept enrollment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Reject an enrollment request
     */
    public function rejectEnrollment()
    {
        $session = session();
        
        // Security: only students can reject enrollment requests
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $userID = $session->get('userID');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $updated = $this->enrollmentModel->rejectEnrollment($enrollmentId, $userID);
            
            if ($updated) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment request rejected',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(404)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Enrollment request not found or already processed',
                        'csrf_hash' => csrf_hash()
                    ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to reject enrollment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Get enrollment details for a course (students with their status)
     */
    public function getEnrollmentDetails($courseId)
    {
        $session = session();
        
        // Security: only teachers can view enrollment details
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $courseId;
        if ($courseId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists and belongs to this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        if ($course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        // Get all enrollments for this course
        $enrollments = $this->enrollmentModel->getCourseEnrollments($courseId);
        
        // Organize by status
        $organized = [
            'accepted' => [],
            'pending' => [],
            'rejected' => []
        ];

        foreach ($enrollments as $enrollment) {
            $status = $enrollment['status'] ?? 'accepted'; // NULL = accepted for backward compatibility
            if ($status === 'accepted' || $status === null) {
                $organized['accepted'][] = $enrollment;
            } elseif ($status === 'pending') {
                $organized['pending'][] = $enrollment;
            } elseif ($status === 'rejected') {
                $organized['rejected'][] = $enrollment;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'course' => [
                'id' => $course['id'],
                'title' => $course['title']
            ],
            'enrollments' => $organized,
            'summary' => [
                'accepted' => count($organized['accepted']),
                'pending' => count($organized['pending']),
                'rejected' => count($organized['rejected']),
                'total' => count($enrollments)
            ],
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Get enrolled students for a course (for students to view classmates)
     */
    public function getEnrolledStudents($courseId)
    {
        $session = session();
        
        // Security: only logged-in users can view enrolled students
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $courseId;
        if ($courseId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // For students: verify they are enrolled in this course
        $userID = $session->get('userID');
        $role = $session->get('role');
        
        if ($role === 'student') {
            if (!$this->enrollmentModel->isAlreadyEnrolled($userID, $courseId)) {
                return $this->response->setStatusCode(403)
                    ->setJSON(['success' => false, 'message' => 'You must be enrolled in this course to view enrolled students', 'csrf_hash' => csrf_hash()]);
            }
        }

        // Get all accepted enrollments for this course (only accepted students)
        $enrollments = $this->enrollmentModel->select('enrollments.id, enrollments.user_id, enrollments.enrollment_date, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->groupStart()
                        ->where('enrollments.status', 'accepted')
                        ->orWhere('enrollments.status IS NULL')
                    ->groupEnd()
                    ->orderBy('enrollments.enrollment_date', 'ASC')
                    ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'course' => [
                'id' => $course['id'],
                'title' => $course['title']
            ],
            'students' => $enrollments,
            'total' => count($enrollments),
            'csrf_hash' => csrf_hash()
        ]);
    }
}
