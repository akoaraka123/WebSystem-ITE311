<?php

namespace App\Controllers;

use App\Models\StudentProgramModel;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\ProgramModel;
use App\Models\AcademicYearModel;
use App\Controllers\BaseController;

class Enrollment extends BaseController
{
    protected $studentProgramModel;
    protected $enrollmentModel;
    protected $courseModel;
    protected $programModel;
    protected $academicYearModel;

    public function __construct()
    {
        $this->studentProgramModel = new StudentProgramModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->courseModel = new CourseModel();
        $this->programModel = new ProgramModel();
        $this->academicYearModel = new AcademicYearModel();
    }

    /**
     * Get courses by program and academic year
     */
    public function getCoursesByProgram()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $programId = $this->request->getPost('program_id');
        $acadYearId = $this->request->getPost('acad_year_id');

        if (!$programId || !$acadYearId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Program ID and Academic Year ID are required', 'csrf_hash' => csrf_hash()]);
        }

        // Get courses for this program and academic year
        $courses = $this->courseModel->where('program_id', $programId)
                                    ->where('acad_year_id', $acadYearId)
                                    ->where('is_active', 1)
                                    ->orderBy('course_number', 'ASC')
                                    ->orderBy('title', 'ASC')
                                    ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'courses' => $courses,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Enroll student in program and optionally in courses
     */
    public function enrollStudent()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $input = json_decode($this->request->getBody(), true);
        
        $studentId = $input['student_id'] ?? null;
        $programId = $input['program_id'] ?? null;
        $acadYearId = $input['acad_year_id'] ?? null; // Optional now
        $courseIds = $input['course_ids'] ?? [];

        // Validate required fields
        if (!$studentId || !$programId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Student ID and Program ID are required', 'csrf_hash' => csrf_hash()]);
        }

        // Verify student exists and is a student
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid student', 'csrf_hash' => csrf_hash()]);
        }

        // Verify program exists
        $program = $this->programModel->find($programId);
        if (!$program || $program['is_active'] != 1) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid program', 'csrf_hash' => csrf_hash()]);
        }

        // Verify academic year exists if provided
        if ($acadYearId) {
            $acadYear = $this->academicYearModel->find($acadYearId);
            if (!$acadYear) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Invalid academic year', 'csrf_hash' => csrf_hash()]);
            }
        }

        // Check if student is already enrolled in ANY program BEFORE attempting enrollment
        $existingEnrollment = $this->studentProgramModel->getStudentActiveEnrollment($studentId);
        
        if ($existingEnrollment) {
            // Student is already enrolled in a program
            $existingProgramCode = $existingEnrollment['program_code'] ?? 'a program';
            $existingProgramName = $existingEnrollment['program_name'] ?? '';
            $newProgramCode = $program['code'] ?? 'the program';
            $newProgramName = $program['name'] ?? '';
            
            // Check if trying to enroll in the same program
            if ($existingEnrollment['program_id'] == $programId) {
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false, 
                        'message' => 'Student is already enrolled in ' . esc($existingProgramCode) . ' (' . esc($existingProgramName) . '). Multiple enrollments are not allowed. A student can only be enrolled once in a program.', 
                        'csrf_hash' => csrf_hash()
                    ]);
            } else {
                // Trying to enroll in a different program
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false, 
                        'message' => 'Student is already enrolled in ' . esc($existingProgramCode) . ' (' . esc($existingProgramName) . '). Cannot enroll in ' . esc($newProgramCode) . ' (' . esc($newProgramName) . '). A student can only be enrolled in one program at a time.', 
                        'csrf_hash' => csrf_hash()
                    ]);
            }
        }

        try {
            // Step 1: Enroll student in program (academic year is optional)
            $programEnrollmentId = $this->studentProgramModel->enrollStudent($studentId, $programId, $acadYearId);
            
            if (!$programEnrollmentId) {
                return $this->response->setStatusCode(500)
                    ->setJSON(['success' => false, 'message' => 'Failed to enroll student in program', 'csrf_hash' => csrf_hash()]);
            }

            $messages = [];
            $messages[] = 'Student enrolled in program ' . $program['code'] . ' successfully.';

            // Step 2: Enroll student in courses (if any)
            if (!empty($courseIds) && is_array($courseIds)) {
                $enrolledCourses = 0;
                $failedCourses = 0;

                foreach ($courseIds as $courseId) {
                    // Verify course exists and belongs to the program
                    $course = $this->courseModel->find($courseId);
                    if (!$course || $course['program_id'] != $programId || $course['acad_year_id'] != $acadYearId) {
                        $failedCourses++;
                        continue;
                    }

                    // Check if already enrolled
                    if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
                        continue; // Skip if already enrolled
                    }

                    // Create enrollment
                    $enrollmentData = [
                        'user_id' => $studentId,
                        'course_id' => $courseId,
                        'enrollment_date' => date('Y-m-d H:i:s'),
                        'status' => 'accepted', // Admin enrollment is auto-accepted
                        'teacher_approved' => 1,
                        'admin_approved' => 1,
                        'teacher_approved_at' => date('Y-m-d H:i:s'),
                        'admin_approved_at' => date('Y-m-d H:i:s')
                    ];

                    if ($this->enrollmentModel->insert($enrollmentData)) {
                        $enrolledCourses++;

                        // Create notification for student
                        $notificationModel = new \App\Models\NotificationModel();
                        $notificationModel->insert([
                            'user_id' => $studentId,
                            'type' => 'enrollment',
                            'title' => 'Enrolled in Course',
                            'message' => 'You have been enrolled in ' . $course['title'] . ' by administrator.',
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    } else {
                        $failedCourses++;
                    }
                }

                if ($enrolledCourses > 0) {
                    $messages[] = "Enrolled in {$enrolledCourses} course(s).";
                }
                if ($failedCourses > 0) {
                    $messages[] = "Failed to enroll in {$failedCourses} course(s).";
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => implode(' ', $messages),
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll student: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Get all enrolled students grouped by program
     */
    public function getEnrolledStudentsByProgram()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $groupedStudents = $this->studentProgramModel->getAllEnrolledStudentsByProgram();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $groupedStudents,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to load enrolled students: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Remove student from program
     */
    public function removeStudentFromProgram()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $input = json_decode($this->request->getBody(), true);
        
        $userId = $input['user_id'] ?? null;
        $programId = $input['program_id'] ?? null;

        if (!$userId || !$programId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'User ID and Program ID are required', 'csrf_hash' => csrf_hash()]);
        }

        try {
            // Find the enrollment
            $enrollment = $this->studentProgramModel->where('user_id', $userId)
                                                   ->where('program_id', $programId)
                                                   ->where('status', 'active')
                                                   ->first();
            
            if (!$enrollment) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Student enrollment not found', 'csrf_hash' => csrf_hash()]);
            }

            // Update status to 'dropped' instead of deleting (for record keeping)
            $this->studentProgramModel->update($enrollment['id'], [
                'status' => 'dropped'
            ]);

            // Get student and program info for message
            $userModel = new \App\Models\UserModel();
            $student = $userModel->find($userId);
            $program = $this->programModel->find($programId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student "' . ($student['name'] ?? 'Unknown') . '" has been removed from ' . ($program['code'] ?? 'program') . ' program.',
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove student from program: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Forward/Transfer student to another program
     */
    public function forwardStudentToProgram()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $input = json_decode($this->request->getBody(), true);
        
        $userId = $input['user_id'] ?? null;
        $currentProgramId = $input['current_program_id'] ?? null;
        $newProgramId = $input['new_program_id'] ?? null;

        if (!$userId || !$currentProgramId || !$newProgramId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'User ID, Current Program ID, and New Program ID are required', 'csrf_hash' => csrf_hash()]);
        }

        if ($currentProgramId == $newProgramId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'New program must be different from current program', 'csrf_hash' => csrf_hash()]);
        }

        try {
            // Verify student exists
            $userModel = new \App\Models\UserModel();
            $student = $userModel->find($userId);
            if (!$student || $student['role'] !== 'student') {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Invalid student', 'csrf_hash' => csrf_hash()]);
            }

            // Verify programs exist
            $currentProgram = $this->programModel->find($currentProgramId);
            $newProgram = $this->programModel->find($newProgramId);
            
            if (!$currentProgram || !$newProgram || $newProgram['is_active'] != 1) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Invalid program', 'csrf_hash' => csrf_hash()]);
            }

            // Check if student is enrolled in current program
            $currentEnrollment = $this->studentProgramModel->where('user_id', $userId)
                                                          ->where('program_id', $currentProgramId)
                                                          ->where('status', 'active')
                                                          ->first();
            
            if (!$currentEnrollment) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Student is not enrolled in the current program', 'csrf_hash' => csrf_hash()]);
            }

            // Check if student is already enrolled in new program
            if ($this->studentProgramModel->isEnrolledInProgram($userId, $newProgramId)) {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Student is already enrolled in ' . $newProgram['code'] . ' program', 'csrf_hash' => csrf_hash()]);
            }

            // Remove from current program (set status to 'transferred')
            $this->studentProgramModel->update($currentEnrollment['id'], [
                'status' => 'transferred'
            ]);

            // Enroll in new program
            $acadYearId = $currentEnrollment['acad_year_id'] ?? null;
            $newEnrollmentId = $this->studentProgramModel->enrollStudent($userId, $newProgramId, $acadYearId);

            if (!$newEnrollmentId) {
                return $this->response->setStatusCode(500)
                    ->setJSON(['success' => false, 'message' => 'Failed to enroll student in new program', 'csrf_hash' => csrf_hash()]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Student "' . $student['name'] . '" has been successfully transferred from ' . $currentProgram['code'] . ' to ' . $newProgram['code'] . ' program.',
                'csrf_hash' => csrf_hash()
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to forward student to program: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }
}

