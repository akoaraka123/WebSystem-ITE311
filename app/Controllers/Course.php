<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;
use App\Models\UserModel;

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
                'school_year' => $this->request->getPost('school_year'),
                'semester' => $this->request->getPost('semester'),
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
                'school_year' => $this->request->getPost('school_year'),
                'semester' => $this->request->getPost('semester'),
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

    // Get available students for a course (not yet enrolled)
    public function getAvailableStudents($courseId)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Verify teacher owns this course
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Get all students
        $userModel = new UserModel();
        $allStudents = $userModel->where('role', 'student')->findAll();

        // Get enrolled student IDs
        $enrolled = $this->enrollmentModel->where('course_id', $courseId)->findAll();
        $enrolledIds = array_column($enrolled, 'user_id');

        // Filter out enrolled students
        $availableStudents = array_filter($allStudents, function($student) use ($enrolledIds) {
            return !in_array($student['id'], $enrolledIds);
        });

        return $this->response->setJSON([
            'success' => true,
            'students' => array_values($availableStudents)
        ]);
    }

    // Add student to course
    public function addStudent()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $courseId = $this->request->getPost('course_id');
        $studentId = $this->request->getPost('student_id');

        if (!$courseId || !$studentId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Course ID and Student ID are required']);
        }

        // Verify teacher owns this course
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Check if student exists and is a student
        $userModel = new UserModel();
        $student = $userModel->where('id', $studentId)->where('role', 'student')->first();
        if (!$student) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Student not found']);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Student is already enrolled in this course'
            ]);
        }

        // Enroll student
        try {
            $this->enrollmentModel->insert([
                'user_id' => $studentId,
                'course_id' => $courseId,
                'enrollment_date' => date('Y-m-d H:i:s')
            ]);

            // Create notification
            $notif = new \App\Models\NotificationModel();
            $notif->add($studentId, 'You have been enrolled in: ' . $course['title']);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student added successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'Failed to add student: ' . $e->getMessage()]);
        }
    }

    // Get enrolled students for a course
    public function getEnrolledStudents($courseId)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // Verify teacher owns this course
        $course = $this->courseModel->find($courseId);
        if (!$course || $course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        // Get enrolled students
        $userModel = new UserModel();
        $enrollments = $this->enrollmentModel->where('course_id', $courseId)->findAll();
        
        $students = [];
        foreach ($enrollments as $enrollment) {
            $student = $userModel->find($enrollment['user_id']);
            if ($student) {
                $students[] = [
                    'id' => $student['id'],
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'enrollment_date' => $enrollment['enrollment_date']
                ];
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'students' => $students
        ]);
    }
}
