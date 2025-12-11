<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\MaterialModel;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\TermModel;

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
            $courses = $this->courseModel->getCoursesWithAcademicInfo();
        } elseif ($role === 'student') {
            // Student sees only enrolled courses
            $enrolled = $this->enrollmentModel->getEnrolledCourses($session->get('userID'));
            $enrolledIds = array_column($enrolled, 'course_id');
            
            if (!empty($enrolledIds)) {
                // Get only enrolled courses with full academic info
                $courses = $this->courseModel->getCoursesWithAcademicInfo($enrolledIds);
            } else {
                // No enrolled courses
                $courses = [];
            }
        } else {
            // Teachers see their own courses
            $courses = $this->courseModel->getTeacherCourses($session->get('userID'));
        }

        // Group courses by program
        $groupedCourses = [];
        $programsList = [];
        
        foreach ($courses as $course) {
            $programId = $course['program_id'] ?? null;
            $programKey = $programId ? $programId : 'no_program';
            $programName = !empty($course['program_code']) 
                ? $course['program_code'] . ' - ' . $course['program_name'] 
                : 'No Program Assigned';
            
            if (!isset($groupedCourses[$programKey])) {
                $groupedCourses[$programKey] = [
                    'program_id' => $programId,
                    'program_name' => $programName,
                    'program_code' => $course['program_code'] ?? '',
                    'courses' => []
                ];
                $programsList[$programKey] = $programName;
            }
            
            $groupedCourses[$programKey]['courses'][] = $course;
        }
        
        // Sort programs (No Program Assigned last)
        uksort($groupedCourses, function($a, $b) {
            if ($a === 'no_program') return 1;
            if ($b === 'no_program') return -1;
            return strcmp($groupedCourses[$a]['program_code'] ?? '', $groupedCourses[$b]['program_code'] ?? '');
        });

        $data = [
            'title' => 'Courses - LMS',
            'courses' => $courses,
            'groupedCourses' => $groupedCourses,
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
            $enrolledCourses = $this->enrollmentModel->getEnrolledCourses($session->get('userID'));
            // Get full course details with academic info
            $courses = [];
            foreach ($enrolledCourses as $enrollment) {
                $course = $this->courseModel->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            courses.schedule_time,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date_start,
                            courses.schedule_date_end,
                            courses.schedule_date,
                            courses.course_number,
                            courses.duration')
                        ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                        ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                        ->join('terms', 'terms.id = courses.term_id', 'left')
                        ->find($enrollment['course_id']);
                if ($course) {
                    $course['enrollment_date'] = $enrollment['created_at'] ?? null;
                    $courses[] = $course;
                }
            }
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
        
        // Only admin can create courses and assign teachers
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Only administrators can create courses.');
            return redirect()->to(base_url('dashboard'));
        }

        // Get all teachers for admin to assign
        $userModel = new \App\Models\UserModel();
        $teachers = $userModel->where('role', 'teacher')->findAll();

        // Get all programs
        $programModel = new \App\Models\ProgramModel();
        $programs = $programModel->getActivePrograms();

        // Get academic years, semesters, and terms
        $acadYearModel = new AcademicYearModel();
        $academicYears = $acadYearModel->getActiveAcademicYears();

        $data = [
            'title' => 'Create Course - LMS',
            'user' => $session->get(),
            'teachers' => $teachers,
            'programs' => $programs,
            'academicYears' => $academicYears,
            'semesters' => [],
            'terms' => []
        ];

        return view('courses/create', $data);
    }

    public function store()
    {
        $session = session();
        
        // Only admin can create courses
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Only administrators can create courses.');
            return redirect()->to(base_url('dashboard'));
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[200]|alpha_numeric_space',
            'description' => 'required|min_length[10]|alpha_numeric_space',
            'teacher_id' => 'required|integer',
            'acad_year_id' => 'required|integer',
            'semester_id' => 'required|integer',
            'term_id' => 'permit_empty|integer',
            'course_number' => 'required|max_length[50]|alpha_numeric_space',
            'schedule_time_start' => 'required',
            'schedule_time_end' => 'required',
            'schedule_date_start' => 'required|valid_date',
            'schedule_date_end' => 'required|valid_date',
            'duration' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[8]',
            'schedule_date' => 'permit_empty|valid_date'
        ];
        
        // Custom error messages
        $messages = [
            'title' => [
                'alpha_numeric_space' => 'Course title can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
            'description' => [
                'alpha_numeric_space' => 'Course description can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
            'course_number' => [
                'alpha_numeric_space' => 'Course number can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
        ];

        if ($this->validate($rules)) {
            $teacherId = (int) $this->request->getPost('teacher_id');

            // Verify teacher exists
            $userModel = new \App\Models\UserModel();
            $teacher = $userModel->find($teacherId);
            if (!$teacher || $teacher['role'] !== 'teacher') {
                $session->setFlashdata('error', 'Invalid teacher selected.');
                return redirect()->to(base_url('create-course'))->withInput();
            }

            // Check for time conflict - REQUIRED validation
            $scheduleTimeStart = $this->request->getPost('schedule_time_start');
            $scheduleTimeEnd = $this->request->getPost('schedule_time_end');
            $scheduleDateStart = $this->request->getPost('schedule_date_start');
            $scheduleDateEnd = $this->request->getPost('schedule_date_end');
            
            // Validate that all schedule fields are provided
            if (empty($scheduleTimeStart) || empty($scheduleTimeEnd) || empty($scheduleDateStart) || empty($scheduleDateEnd)) {
                $session->setFlashdata('error', 'Schedule time and dates are required.');
                return redirect()->to(base_url('create-course'))->withInput();
            }
            
            // Get academic year ID for conflict check (only check conflicts within same academic year)
            $acadYearId = $this->request->getPost('acad_year_id');
            $acadYearId = !empty($acadYearId) ? (int) $acadYearId : null;
            
            // Check for time conflict with same teacher, same date, same academic year, and same/overlapping time
            // Use schedule_date_start for conflict checking
            $conflict = $this->courseModel->checkTeacherTimeConflict(
                $teacherId, 
                $scheduleTimeStart, 
                $scheduleTimeEnd, 
                $scheduleDateStart,
                null, // No course to exclude (creating new)
                $acadYearId // Only check conflicts within same academic year
            );
            
            if ($conflict) {
                $conflictStart = $conflict['schedule_time_start'] ?? $conflict['schedule_time'] ?? '';
                $conflictEnd = $conflict['schedule_time_end'] ?? '';
                
                // Format time for display
                if ($conflictStart) {
                    $conflictStartFormatted = date('g:i A', strtotime($conflictStart));
                    if ($conflictEnd) {
                        $conflictEndFormatted = date('g:i A', strtotime($conflictEnd));
                        $conflictTime = $conflictStartFormatted . ' - ' . $conflictEndFormatted;
                    } else {
                        $conflictTime = $conflictStartFormatted;
                    }
                } else {
                    $conflictTime = 'Unknown time';
                }
                
                $session->setFlashdata('error', 
                    '❌ Time Conflict Detected! The teacher is already assigned to course "' . esc($conflict['title']) . 
                    '" at ' . esc($conflictTime) . ' on ' . date('M d, Y', strtotime($scheduleDateStart)) . 
                    '. Please choose a different time or date.');
                return redirect()->to(base_url('create-course'))->withInput();
            }

            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'teacher_id' => $teacherId,
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Add program_id if provided
            $programId = $this->request->getPost('program_id');
            if (!empty($programId)) {
                $programModel = new \App\Models\ProgramModel();
                $program = $programModel->find($programId);
                if ($program) {
                    $data['program_id'] = (int) $programId;
                }
            }

            // Add academic year, semester, term, course number, and schedule
            $acadYearId = $this->request->getPost('acad_year_id');
            if (!empty($acadYearId)) {
                $data['acad_year_id'] = (int) $acadYearId;
            }

            $semesterId = $this->request->getPost('semester_id');
            if (!empty($semesterId)) {
                $data['semester_id'] = (int) $semesterId;
            }

            $termId = $this->request->getPost('term_id');
            if (!empty($termId)) {
                $data['term_id'] = (int) $termId;
            }

            $courseNumber = $this->request->getPost('course_number');
            if (!empty($courseNumber)) {
                $data['course_number'] = trim($courseNumber);
            }

            // Save schedule times
            $scheduleTimeStart = $this->request->getPost('schedule_time_start');
            if (!empty($scheduleTimeStart)) {
                $data['schedule_time_start'] = $scheduleTimeStart;
                // Keep schedule_time for backward compatibility (use start time)
                $data['schedule_time'] = $scheduleTimeStart;
            }

            $scheduleTimeEnd = $this->request->getPost('schedule_time_end');
            if (!empty($scheduleTimeEnd)) {
                $data['schedule_time_end'] = $scheduleTimeEnd;
            }

            $scheduleDate = $this->request->getPost('schedule_date');
            if (!empty($scheduleDate)) {
                $data['schedule_date'] = $scheduleDate;
            }

            $scheduleDateStart = $this->request->getPost('schedule_date_start');
            if (!empty($scheduleDateStart)) {
                $data['schedule_date_start'] = $scheduleDateStart;
            }

            $scheduleDateEnd = $this->request->getPost('schedule_date_end');
            if (!empty($scheduleDateEnd)) {
                $data['schedule_date_end'] = $scheduleDateEnd;
            }

            $courseId = $this->courseModel->insert($data);
            
            // Notify the teacher that they have been assigned to a new course
            if ($courseId && $teacherId) {
                $notificationModel = new \App\Models\NotificationModel();
                $courseTitle = $this->request->getPost('title');
                $notificationModel->add($teacherId, '📚 You have been assigned to teach the course: ' . esc($courseTitle) . '. Please check your courses to view details.');
            }
            
            $session->setFlashdata('success', 'Course created successfully and assigned to teacher!');
            
            return redirect()->to(base_url('courses'));
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

        // Get academic years, semesters, and terms
        $acadYearModel = new AcademicYearModel();
        $academicYears = $acadYearModel->getActiveAcademicYears();

        $semesterModel = new SemesterModel();
        $semesters = [];
        if (!empty($course['acad_year_id'])) {
            $semesters = $semesterModel->getSemestersByAcademicYear($course['acad_year_id']);
        }

        $termModel = new TermModel();
        $terms = [];
        if (!empty($course['semester_id'])) {
            $terms = $termModel->getTermsBySemester($course['semester_id']);
        }

        // Get validation errors from session if any (from failed validation)
        $validationErrors = $session->getFlashdata('validation_errors');
        $validator = null;
        if (!empty($validationErrors)) {
            // Create a validator instance to display errors
            $validator = \Config\Services::validation();
            foreach ($validationErrors as $field => $error) {
                $validator->setError($field, $error);
            }
        }

        $data = [
            'title' => 'Edit Course - LMS',
            'course' => $course,
            'user' => $session->get(),
            'teachers' => [],
            'programs' => [],
            'academicYears' => $academicYears,
            'semesters' => $semesters,
            'terms' => $terms,
            'validation' => $validator
        ];

        // For admin users, fetch teachers and programs
        if ($session->get('role') === 'admin') {
            $userModel = new \App\Models\UserModel();
            $data['teachers'] = $userModel->where('role', 'teacher')
                                        ->select('id, name, email')
                                        ->orderBy('name', 'ASC')
                                        ->findAll();
            $programModel = new \App\Models\ProgramModel();
            $data['programs'] = $programModel->getAllPrograms();
        }

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
            'title' => 'required|min_length[3]|max_length[200]|alpha_numeric_space',
            'description' => 'required|min_length[10]|alpha_numeric_space',
            'acad_year_id' => 'required|integer',
            'semester_id' => 'required|integer',
            'course_number' => 'required|max_length[50]|alpha_numeric_space',
            'schedule_time_start' => 'required',
            'schedule_time_end' => 'required',
            'schedule_date_start' => 'required|valid_date',
            'schedule_date_end' => 'required|valid_date',
            'duration' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[8]',
            'schedule_date' => 'permit_empty|valid_date'
        ];
        
        // Custom error messages
        $messages = [
            'title' => [
                'alpha_numeric_space' => 'Course title can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
            'description' => [
                'alpha_numeric_space' => 'Course description can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
            'course_number' => [
                'alpha_numeric_space' => 'Course number can only contain letters, numbers, and spaces. Special characters are not allowed.',
            ],
        ];

        if ($this->validate($rules, $messages)) {
            // Get teacher ID (may be updated by admin)
            $teacherId = $course['teacher_id']; // Default to current teacher
            if ($session->get('role') === 'admin') {
                $newTeacherId = $this->request->getPost('teacher_id');
                if (!empty($newTeacherId)) {
                    $teacherId = (int) $newTeacherId;
                }
            }

            // Check for time conflict before updating - REQUIRED validation
            $scheduleTimeStart = $this->request->getPost('schedule_time_start');
            $scheduleTimeEnd = $this->request->getPost('schedule_time_end');
            $scheduleDateStart = $this->request->getPost('schedule_date_start');
            $scheduleDateEnd = $this->request->getPost('schedule_date_end');
            
            // Validate that all schedule fields are provided
            if (empty($scheduleTimeStart) || empty($scheduleTimeEnd) || empty($scheduleDateStart) || empty($scheduleDateEnd)) {
                $session->setFlashdata('error', 'Schedule time and dates are required.');
                return redirect()->to(base_url('edit-course/' . $id))->withInput();
            }
            
            // Get academic year ID for conflict check (only check conflicts within same academic year)
            $acadYearId = $this->request->getPost('acad_year_id');
            $acadYearId = !empty($acadYearId) ? (int) $acadYearId : null;
            
            // Check for time conflict with same teacher, same date, same academic year, and same/overlapping time
            // Use schedule_date_start for conflict checking (or schedule_date if available for backward compatibility)
            $conflictDate = $scheduleDateStart ?? $this->request->getPost('schedule_date') ?? null;
            $conflict = $this->courseModel->checkTeacherTimeConflict(
                $teacherId, 
                $scheduleTimeStart, 
                $scheduleTimeEnd, 
                $conflictDate,
                $id, // Exclude current course from conflict check
                $acadYearId // Only check conflicts within same academic year
            );
            
            if ($conflict) {
                $conflictStart = $conflict['schedule_time_start'] ?? $conflict['schedule_time'] ?? '';
                $conflictEnd = $conflict['schedule_time_end'] ?? '';
                
                // Format time for display
                if ($conflictStart) {
                    $conflictStartFormatted = date('g:i A', strtotime($conflictStart));
                    if ($conflictEnd) {
                        $conflictEndFormatted = date('g:i A', strtotime($conflictEnd));
                        $conflictTime = $conflictStartFormatted . ' - ' . $conflictEndFormatted;
                    } else {
                        $conflictTime = $conflictStartFormatted;
                    }
                } else {
                    $conflictTime = 'Unknown time';
                }
                
                $session->setFlashdata('error', 
                    '❌ Time Conflict Detected! The teacher is already assigned to course "' . esc($conflict['title']) . 
                    '" at ' . esc($conflictTime) . ' on ' . date('M d, Y', strtotime($conflictDate)) . 
                    '. Please choose a different time or date.');
                return redirect()->to(base_url('edit-course/' . $id))->withInput();
            }

            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // For admin users, allow updating teacher_id and program_id
            if ($session->get('role') === 'admin') {
                if (!empty($teacherId)) {
                    $data['teacher_id'] = $teacherId;
                }
                
                $programId = $this->request->getPost('program_id');
                if (!empty($programId)) {
                    $data['program_id'] = (int) $programId;
                } else {
                    $data['program_id'] = null; // Allow clearing program assignment
                }
            }

            // Update academic year, semester, term, course number, and schedule
            $acadYearId = $this->request->getPost('acad_year_id');
            $data['acad_year_id'] = !empty($acadYearId) ? (int) $acadYearId : null;

            $semesterId = $this->request->getPost('semester_id');
            $data['semester_id'] = !empty($semesterId) ? (int) $semesterId : null;

            $termId = $this->request->getPost('term_id');
            $data['term_id'] = !empty($termId) ? (int) $termId : null;

            $courseNumber = $this->request->getPost('course_number');
            $data['course_number'] = !empty($courseNumber) ? trim($courseNumber) : null;

            $scheduleTimeStart = $this->request->getPost('schedule_time_start');
            if (!empty($scheduleTimeStart)) {
                $data['schedule_time_start'] = $scheduleTimeStart;
                // Keep schedule_time for backward compatibility (use start time)
                $data['schedule_time'] = $scheduleTimeStart;
            }

            $scheduleTimeEnd = $this->request->getPost('schedule_time_end');
            if (!empty($scheduleTimeEnd)) {
                $data['schedule_time_end'] = $scheduleTimeEnd;
            }

            $duration = $this->request->getPost('duration');
            $data['duration'] = !empty($duration) ? (int) $duration : null;

            $scheduleDate = $this->request->getPost('schedule_date');
            $data['schedule_date'] = !empty($scheduleDate) ? $scheduleDate : null;

            $scheduleDateStart = $this->request->getPost('schedule_date_start');
            $data['schedule_date_start'] = !empty($scheduleDateStart) ? $scheduleDateStart : null;

            $scheduleDateEnd = $this->request->getPost('schedule_date_end');
            $data['schedule_date_end'] = !empty($scheduleDateEnd) ? $scheduleDateEnd : null;

            // Check if teacher was changed (admin only)
            $oldTeacherId = $course['teacher_id'] ?? null;
            $newTeacherId = null;
            if ($session->get('role') === 'admin' && !empty($teacherId)) {
                $newTeacherId = $teacherId;
            }
            
            $this->courseModel->update($id, $data);
            
            // Notify teacher if assignment changed (admin only)
            if ($session->get('role') === 'admin' && !empty($newTeacherId) && $newTeacherId != $oldTeacherId) {
                $notificationModel = new \App\Models\NotificationModel();
                $courseTitle = $this->request->getPost('title') ?? $course['title'] ?? 'a course';
                
                // Notify new teacher
                $notificationModel->add($newTeacherId, '📚 You have been assigned to teach the course: ' . esc($courseTitle) . '. Please check your courses to view details.');
                
                // Notify old teacher if different
                if (!empty($oldTeacherId) && $oldTeacherId != $newTeacherId) {
                    $notificationModel->add((int)$oldTeacherId, '⚠️ You have been unassigned from the course: ' . esc($courseTitle) . '.');
                }
            }
            
            $session->setFlashdata('success', 'Course updated successfully!');
            
            $redirectTo = $session->get('role') === 'admin' ? base_url('courses') : base_url('my-courses');
            return redirect()->to($redirectTo);
        } else {
            // Store validation errors in session and redirect with input
            $session->setFlashdata('validation_errors', $this->validator->getErrors());
            return redirect()->to(base_url('edit-course/' . $id))->withInput();
        }
    }

    public function view($id)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $course = $this->courseModel->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            courses.schedule_time,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date,
                            courses.schedule_date_start,
                            courses.schedule_date_end,
                            courses.course_number')
                    ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                    ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                    ->join('terms', 'terms.id = courses.term_id', 'left')
                    ->find($id);
        
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('courses'));
        }

        // Access control for students - must be enrolled
        $role = $session->get('role');
        if ($role === 'student') {
            $userID = $session->get('userID');
            $enrollmentModel = new \App\Models\EnrollmentModel();
            $enrollment = $enrollmentModel->where('user_id', $userID)
                                         ->where('course_id', $course['id'])
                                         ->where('status', 'accepted')
                                         ->where('teacher_approved', 1)
                                         ->first();
            
            if (!$enrollment) {
                $session->setFlashdata('error', '❌ Access denied. You are not enrolled in this course or your enrollment is not approved.');
                return redirect()->to(base_url('courses'));
            }
        } elseif ($role === 'teacher') {
            // Teachers can only view their own courses
            $userID = $session->get('userID');
            if ($course['teacher_id'] != $userID) {
                $session->setFlashdata('error', '❌ Access denied. This course is not assigned to you.');
                return redirect()->to(base_url('courses'));
            }
        }
        // Admins can view any course

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
            return $this->response->setJSON(['success' => false, 'message' => 'You are already enrolled in this course or have a pending enrollment request.', 'csrf_hash' => csrf_hash()]);
        }

        // Check if student is enrolled in a program first
        $studentProgramModel = new \App\Models\StudentProgramModel();
        $studentProgram = $studentProgramModel->getStudentProgram($userID);
        
        if (!empty($course['program_id'])) {
            // Check if student is enrolled in the program
            if (!$studentProgram || $studentProgram['program_id'] != $course['program_id']) {
                $programModel = new \App\Models\ProgramModel();
                $requiredProgram = $programModel->find($course['program_id']);
                $programName = $requiredProgram ? $requiredProgram['code'] : 'Unknown';
                
                if (!$studentProgram) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'You must be enrolled in ' . esc($programName) . ' program first before enrolling in courses. Please contact administrator to enroll you in the program.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                } else {
                    $currentProgram = $programModel->find($studentProgram['program_id']);
                    $currentProgramName = $currentProgram ? $currentProgram['code'] : 'Unknown';
                    
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'Program restriction: You are enrolled in ' . esc($currentProgramName) . ' program. Cannot enroll in ' . esc($programName) . ' program courses. You can only enroll in courses from the same program.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        }

        // Create pending enrollment (requires teacher approval)
        $this->enrollmentModel->enrollUser([
            'user_id' => $userID,
            'course_id' => $courseID,
            'enrollment_date' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'teacher_approved' => 0,
            'admin_approved' => 0
        ]);

        // Create notifications
        $notif = new \App\Models\NotificationModel();
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($userID);
        
        // Notify the student
        $notif->add($userID, 'Your enrollment request for "' . ($course['title'] ?? 'a course') . '" is pending approval from the teacher.');
        
        // Notify the course teacher
        if (!empty($course['teacher_id'])) {
            $notif->add((int)$course['teacher_id'], 'A student (' . ($student['name'] ?? 'Unknown') . ') requested enrollment in your course: ' . ($course['title'] ?? '') . '. Please approve or reject.');
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Enrollment request submitted! Waiting for teacher approval.', 'csrf_hash' => csrf_hash()]);
    }

    public function delete($id = null)
    {
        $session = session();
        
        // Security: only admin can delete courses
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized to delete courses. Only administrators can delete courses.');
            return redirect()->to(base_url('dashboard'));
        }

        // Validate course ID
        if (!$id || !is_numeric($id)) {
            $session->setFlashdata('error', 'Invalid course ID.');
            return redirect()->to(base_url('courses'));
        }

        $userID = $session->get('userID');
        
        // Check if course exists
        $course = $this->courseModel->find($id);
        if (!$course) {
            $session->setFlashdata('error', 'Course not found.');
            return redirect()->to(base_url('courses'));
        }

        try {
            // Delete all enrollments for this course
            $this->enrollmentModel->where('course_id', $id)->delete();
            
            // Delete all materials for this course
            $materialModel = new \App\Models\MaterialModel();
            $materialModel->where('course_id', $id)->delete();
            
            // Delete the course
            $this->courseModel->delete($id);
            
            // Create notification for the admin
            $notif = new \App\Models\NotificationModel();
            $notif->add($userID, 'You deleted the course: ' . ($course['title'] ?? 'Untitled Course'));
            
            // Also notify the teacher if course had a teacher assigned
            if (!empty($course['teacher_id'])) {
                $notif->add($course['teacher_id'], 'Your course "' . ($course['title'] ?? 'Untitled Course') . '" was deleted by an administrator.');
            }
            
            $session->setFlashdata('success', 'Course and all related data deleted successfully.');
            
        } catch (\Exception $e) {
            $session->setFlashdata('error', 'Failed to delete course: ' . $e->getMessage());
        }

        return redirect()->to(base_url('courses'));
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

        // Check if student is enrolled in a program first
        $studentProgramModel = new \App\Models\StudentProgramModel();
        $studentProgram = $studentProgramModel->getStudentProgram($studentId);
        
        if (!empty($course['program_id'])) {
            // Check if student is enrolled in the program
            if (!$studentProgram || $studentProgram['program_id'] != $course['program_id']) {
                $programModel = new \App\Models\ProgramModel();
                $requiredProgram = $programModel->find($course['program_id']);
                $programName = $requiredProgram ? $requiredProgram['code'] . ' - ' . $requiredProgram['name'] : 'Unknown';
                
                if (!$studentProgram) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => '❌ ENROLLMENT FAILED: Student is NOT enrolled in any program. The student must be enrolled in ' . esc($programName) . ' program FIRST before they can be added to this course. Please contact administrator to enroll the student in the program first.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                } else {
                    $currentProgram = $programModel->find($studentProgram['program_id']);
                    $currentProgramName = $currentProgram ? $currentProgram['code'] . ' - ' . $currentProgram['name'] : 'Unknown';
                    
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => '❌ ENROLLMENT FAILED: Student is enrolled in ' . esc($currentProgramName) . ' program. Cannot add student to ' . esc($programName) . ' program course. Students can ONLY enroll in courses from the SAME program they are enrolled in.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        }

        // Check if there's a rejected enrollment - if so, update it to accepted instead of creating new
        $rejectedEnrollment = $this->enrollmentModel->where('user_id', $studentId)
                                                     ->where('course_id', $courseId)
                                                     ->where('status', 'rejected')
                                                     ->first();

        // Teacher enrolls student - creates pending enrollment, student needs to accept/reject
        try {
            if ($rejectedEnrollment) {
                // Update rejected enrollment to pending (allows teacher to re-add after student rejected)
                $this->enrollmentModel->update($rejectedEnrollment['id'], [
                    'status' => 'pending',
                    'teacher_approved' => 1, // Teacher has approved
                    'teacher_approved_at' => date('Y-m-d H:i:s'),
                    'admin_approved' => 0, // Not admin-initiated, so we can distinguish
                    'enrollment_date' => date('Y-m-d H:i:s')
                ]);
            } else {
                // Create new pending enrollment - teacher enrolls student, student needs to accept/reject
                $this->enrollmentModel->enrollUser([
                    'user_id' => $studentId,
                    'course_id' => $courseId,
                    'enrollment_date' => date('Y-m-d H:i:s'),
                    'status' => 'pending',
                    'teacher_approved' => 1, // Teacher has approved
                    'teacher_approved_at' => date('Y-m-d H:i:s'),
                    'admin_approved' => 0 // Not admin-initiated, so we can distinguish
                ]);
            }

            // Create notifications
            $notif = new \App\Models\NotificationModel();
            $notif->add($studentId, 'You have a new enrollment request for: ' . ($course['title'] ?? 'a course') . ' from your teacher. Please accept or reject it.');
            if (!empty($course['teacher_id'])) {
                $notif->add((int)$course['teacher_id'], 'You sent an enrollment request to a student for your course: ' . ($course['title'] ?? ''));
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request sent to student. They need to accept or reject it.',
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
            // Get enrollment info BEFORE updating to check admin_approved status
            $enrollment = $this->enrollmentModel->find($enrollmentId);
            if (!$enrollment) {
                return $this->response->setStatusCode(404)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Enrollment not found',
                        'csrf_hash' => csrf_hash()
                    ]);
            }
            
            // Check if enrollment was admin-initiated or teacher-initiated BEFORE updating
            // Check for both integer 1 and string "1" to be safe
            $isAdminInitiated = !empty($enrollment['admin_approved']) && 
                               ($enrollment['admin_approved'] == 1 || $enrollment['admin_approved'] === '1' || (int)$enrollment['admin_approved'] === 1);
            $isTeacherInitiated = !empty($enrollment['teacher_approved']) && 
                                 ($enrollment['teacher_approved'] == 1 || $enrollment['teacher_approved'] === '1' || (int)$enrollment['teacher_approved'] === 1);
            
            // Get course and student info for notifications
            $course = $this->courseModel->find($enrollment['course_id']);
            $userModel = new \App\Models\UserModel();
            $student = $userModel->find($enrollment['user_id']);
            
            // Now update the enrollment
            $updated = $this->enrollmentModel->acceptEnrollment($enrollmentId, $userID);
            
            if ($updated) {
                $notificationModel = new \App\Models\NotificationModel();
                $courseTitle = $course['title'] ?? 'a course';
                $studentName = $student['name'] ?? 'A student';
                
                // Notify the teacher (if course has a teacher assigned)
                if (!empty($course['teacher_id'])) {
                    $notificationModel->add((int)$course['teacher_id'], '✅ Student "' . esc($studentName) . '" has accepted enrollment in your course: ' . esc($courseTitle) . '.');
                }
                
                // Notify all admins (always notify admins when student accepts enrollment)
                $admins = $userModel->where('role', 'admin')
                                   ->select('id')
                                   ->findAll();
                
                // Send notification to all admins
                if (!empty($admins)) {
                    foreach ($admins as $admin) {
                        $notificationModel->add((int)$admin['id'], '✅ Student "' . esc($studentName) . '" has accepted enrollment in the course: ' . esc($courseTitle) . '.');
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
        
        // Security: only teachers and admin can view enrollment details
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }
        
        $role = $session->get('role');
        if ($role !== 'teacher' && $role !== 'admin') {
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

        // For teachers: verify course belongs to this teacher
        // For admin: allow access to all courses
        if ($role === 'teacher' && $course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        // Get all enrollments for this course
        $enrollments = $this->enrollmentModel->getCourseEnrollments($courseId);
        
        // Organize by status and approval
        $organized = [
            'accepted' => [],
            'pending' => [], // Waiting for teacher approval
            'rejected' => []
        ];

        foreach ($enrollments as $enrollment) {
            $status = $enrollment['status'] ?? 'pending';
            $teacherApproved = (int)($enrollment['teacher_approved'] ?? 0);
            
            if ($status === 'accepted' && $teacherApproved == 1) {
                $organized['accepted'][] = $enrollment;
            } elseif ($status === 'pending' && $teacherApproved == 0) {
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

        // Get all teacher-approved enrollments for this course
        $enrollments = $this->enrollmentModel->select('enrollments.id, enrollments.user_id, enrollments.enrollment_date, users.name as student_name, users.email as student_email')
                    ->join('users', 'users.id = enrollments.user_id')
                    ->where('enrollments.course_id', $courseId)
                    ->where('enrollments.status', 'accepted')
                    ->where('enrollments.teacher_approved', 1)
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

    /**
     * Admin: Enroll student to course (direct enrollment, no pending status)
     */
    public function adminEnrollStudent()
    {
        $session = session();
        
        // Security: only admin can directly enroll students
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        $studentId = (int) $this->request->getPost('student_id');

        if ($courseId <= 0 || $studentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course or student ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // Verify student exists and is actually a student
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Student not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if already enrolled
        if ($this->enrollmentModel->isAlreadyEnrolled($studentId, $courseId)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Student is already enrolled in this course', 
                'csrf_hash' => csrf_hash()
            ]);
        }

        // Check if student is enrolled in a program first
        $studentProgramModel = new \App\Models\StudentProgramModel();
        $studentProgram = $studentProgramModel->getStudentProgram($studentId);
        
        if (!empty($course['program_id'])) {
            // Check if student is enrolled in the program
            if (!$studentProgram || $studentProgram['program_id'] != $course['program_id']) {
                $programModel = new \App\Models\ProgramModel();
                $requiredProgram = $programModel->find($course['program_id']);
                $programName = $requiredProgram ? $requiredProgram['code'] . ' - ' . $requiredProgram['name'] : 'Unknown';
                
                if (!$studentProgram) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => '❌ ENROLLMENT FAILED: Student is NOT enrolled in any program. The student must be enrolled in ' . esc($programName) . ' program FIRST before they can be added to this course. Please enroll the student in the program first using "Enroll Student Program" feature.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                } else {
                    $currentProgram = $programModel->find($studentProgram['program_id']);
                    $currentProgramName = $currentProgram ? $currentProgram['code'] . ' - ' . $currentProgram['name'] : 'Unknown';
                    
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => '❌ ENROLLMENT FAILED: Student is enrolled in ' . esc($currentProgramName) . ' program. Cannot add student to ' . esc($programName) . ' program course. Students can ONLY enroll in courses from the SAME program they are enrolled in.', 
                        'csrf_hash' => csrf_hash()
                    ]);
                }
            }
        }

        // Admin enrolls student - creates pending enrollment, student needs to accept/reject
        try {
            $this->enrollmentModel->enrollUser([
                'user_id' => $studentId,
                'course_id' => $courseId,
                'enrollment_date' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'teacher_approved' => 0, // Not teacher-initiated, so we can distinguish
                'admin_approved' => 1, // Admin has approved
                'admin_approved_at' => date('Y-m-d H:i:s')
            ]);

            // Create notifications
            $notif = new \App\Models\NotificationModel();
            $notif->add($studentId, 'You have a new enrollment request for: ' . ($course['title'] ?? 'a course') . ' from administrator. Please accept or reject it.');
            if (!empty($course['teacher_id'])) {
                $notif->add((int)$course['teacher_id'], 'Admin sent an enrollment request to a student for your course: ' . ($course['title'] ?? ''));
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Enrollment request sent to student. They need to accept or reject it.',
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
     * Admin: Remove student from course
     */
    public function adminRemoveStudent()
    {
        $session = session();
        
        // Security: only admin can remove students
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        $studentId = (int) $this->request->getPost('student_id');

        if ($courseId <= 0 || $studentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course or student ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // Verify student exists and is actually a student
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Student not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if student is enrolled
        $enrollment = $this->enrollmentModel->where('user_id', $studentId)
                                           ->where('course_id', $courseId)
                                           ->first();
        
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Student is not enrolled in this course', 
                'csrf_hash' => csrf_hash()
            ]);
        }

        // Remove enrollment
        try {
            $removed = $this->enrollmentModel->unenroll($studentId, $courseId);
            
            if ($removed) {
                // Create notification for student
                $notif = new \App\Models\NotificationModel();
                $notif->add($studentId, 'You have been removed from the course: ' . ($course['title'] ?? 'a course'));
                
                // Notify teacher if course has one
                if (!empty($course['teacher_id'])) {
                    $notif->add((int)$course['teacher_id'], 'Admin removed a student (' . ($student['name'] ?? 'Unknown') . ') from your course: ' . ($course['title'] ?? ''));
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student removed from course successfully',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove student',
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove student: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Teacher/Admin: Remove student from course
     */
    public function teacherRemoveStudent()
    {
        $session = session();
        
        // Security: only teachers and admin can remove students
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }
        
        $role = $session->get('role');
        if ($role !== 'teacher' && $role !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        $studentId = (int) $this->request->getPost('student_id');

        if ($courseId <= 0 || $studentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course or student ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // For teachers: verify course belongs to this teacher
        // For admin: allow access to all courses
        if ($role === 'teacher' && $course['teacher_id'] != $session->get('userID')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied. You can only remove students from your own courses.', 'csrf_hash' => csrf_hash()]);
        }

        // Verify student exists and is actually a student
        $userModel = new \App\Models\UserModel();
        $student = $userModel->find($studentId);
        if (!$student || $student['role'] !== 'student') {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Student not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if student is enrolled
        $enrollment = $this->enrollmentModel->where('user_id', $studentId)
                                           ->where('course_id', $courseId)
                                           ->first();
        
        if (!$enrollment) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Student is not enrolled in this course', 
                'csrf_hash' => csrf_hash()
            ]);
        }

        // Remove enrollment
        try {
            $removed = $this->enrollmentModel->unenroll($studentId, $courseId);
            
            if ($removed) {
                // Create notification for student
                $notif = new \App\Models\NotificationModel();
                $removedBy = ($role === 'admin') ? 'admin' : 'your teacher';
                $notif->add($studentId, 'You have been removed from the course: ' . ($course['title'] ?? 'a course') . ' by ' . $removedBy . '.');
                
                // If admin removed, also notify the teacher
                if ($role === 'admin' && !empty($course['teacher_id'])) {
                    $notif->add((int)$course['teacher_id'], 'Admin removed a student (' . ($student['name'] ?? 'Unknown') . ') from your course: ' . ($course['title'] ?? ''));
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Student removed from course successfully',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove student',
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to remove student: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Admin: Assign or reassign teacher to course
     */
    public function assignTeacher()
    {
        $session = session();
        
        // Security: only admin can assign teachers
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $courseId = (int) $this->request->getPost('course_id');
        $teacherId = (int) $this->request->getPost('teacher_id');

        if ($courseId <= 0 || $teacherId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course or teacher ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // Verify teacher exists and is actually a teacher
        $userModel = new \App\Models\UserModel();
        $teacher = $userModel->find($teacherId);
        if (!$teacher || $teacher['role'] !== 'teacher') {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Teacher not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check for time conflict if course has schedule
        $scheduleTimeStart = $course['schedule_time_start'] ?? $course['schedule_time'] ?? null;
        $scheduleTimeEnd = $course['schedule_time_end'] ?? null;
        $scheduleDate = $course['schedule_date'] ?? null;
        $acadYearId = $course['acad_year_id'] ?? null; // Get academic year from course
        
        if (!empty($scheduleTimeStart) && !empty($scheduleDate)) {
            $conflict = $this->courseModel->checkTeacherTimeConflict(
                $teacherId, 
                $scheduleTimeStart, 
                $scheduleTimeEnd, 
                $scheduleDate,
                $courseId, // Exclude current course from conflict check
                $acadYearId // Only check conflicts within same academic year
            );
            
            if ($conflict) {
                $conflictStart = $conflict['schedule_time_start'] ?? $conflict['schedule_time'] ?? '';
                $conflictEnd = $conflict['schedule_time_end'] ?? '';
                
                // Format time for display
                if ($conflictStart) {
                    $conflictStartFormatted = date('g:i A', strtotime($conflictStart));
                    if ($conflictEnd) {
                        $conflictEndFormatted = date('g:i A', strtotime($conflictEnd));
                        $conflictTime = $conflictStartFormatted . ' - ' . $conflictEndFormatted;
                    } else {
                        $conflictTime = $conflictStartFormatted;
                    }
                } else {
                    $conflictTime = 'Unknown time';
                }
                
                return $this->response->setStatusCode(400)
                    ->setJSON([
                        'success' => false, 
                        'message' => '❌ Time Conflict Detected! The teacher is already assigned to course "' . esc($conflict['title']) . 
                                    '" at ' . esc($conflictTime) . ' on ' . date('M d, Y', strtotime($scheduleDate)) . 
                                    '. Please choose a different teacher or update the course schedule.',
                        'csrf_hash' => csrf_hash()
                    ]);
            }
        }

        try {
            // Create pending teacher assignment (requires teacher approval)
            $this->courseModel->update($courseId, [
                'pending_teacher_id' => $teacherId,
                'teacher_assignment_status' => 'pending',
                'teacher_assignment_requested_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Notify the teacher about pending assignment
            $notificationModel = new \App\Models\NotificationModel();
            $courseTitle = $course['title'] ?? 'a course';
            $notificationModel->add($teacherId, '📚 You have been assigned to teach the course: ' . esc($courseTitle) . '. Please accept or reject this assignment.');

            // Notify old teacher if different and was already assigned
            if (!empty($course['teacher_id']) && $course['teacher_id'] != $teacherId) {
                $notificationModel->add((int)$course['teacher_id'], '⚠️ You have been unassigned from the course: ' . esc($courseTitle) . '.');
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Teacher assignment request sent. The teacher needs to accept or reject it.',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to assign teacher: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Teacher: Accept teacher assignment
     */
    public function acceptTeacherAssignment()
    {
        $session = session();
        
        // Security: only teachers can accept assignments
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        // Get data from JSON body
        $input = json_decode($this->request->getBody(), true);
        $courseId = (int) ($input['course_id'] ?? 0);
        $teacherId = $session->get('userID');

        if ($courseId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists and has pending assignment for this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if this teacher has a pending assignment
        if (empty($course['pending_teacher_id']) || $course['pending_teacher_id'] != $teacherId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'No pending assignment found for this teacher', 'csrf_hash' => csrf_hash()]);
        }

        if ($course['teacher_assignment_status'] !== 'pending') {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Assignment is not pending', 'csrf_hash' => csrf_hash()]);
        }

        try {
            // Accept the assignment - set teacher_id and clear pending fields
            $this->courseModel->update($courseId, [
                'teacher_id' => $teacherId,
                'pending_teacher_id' => null,
                'teacher_assignment_status' => 'accepted',
                'teacher_assignment_requested_at' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Notify all admins
            $notificationModel = new \App\Models\NotificationModel();
            $courseTitle = $course['title'] ?? 'a course';
            $userModel = new \App\Models\UserModel();
            $teacher = $userModel->find($teacherId);
            $teacherName = $teacher['name'] ?? 'Teacher';
            
            // Get all admin users
            $admins = $userModel->where('role', 'admin')
                               ->select('id')
                               ->findAll();
            
            // Send notification to all admins
            if (!empty($admins)) {
                foreach ($admins as $admin) {
                    $notificationModel->add((int)$admin['id'], '✅ Teacher "' . esc($teacherName) . '" has accepted assignment to teach the course: ' . esc($courseTitle) . '.');
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Assignment accepted successfully',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to accept assignment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Teacher: Reject teacher assignment
     */
    public function rejectTeacherAssignment()
    {
        $session = session();
        
        // Security: only teachers can reject assignments
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        // Get data from JSON body
        $input = json_decode($this->request->getBody(), true);
        $courseId = (int) ($input['course_id'] ?? 0);
        $teacherId = $session->get('userID');

        if ($courseId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid course ID', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course exists and has pending assignment for this teacher
        $course = $this->courseModel->find($courseId);
        if (!$course) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Course not found', 'csrf_hash' => csrf_hash()]);
        }

        // Check if this teacher has a pending assignment
        if (empty($course['pending_teacher_id']) || $course['pending_teacher_id'] != $teacherId) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'No pending assignment found for this teacher', 'csrf_hash' => csrf_hash()]);
        }

        if ($course['teacher_assignment_status'] !== 'pending') {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Assignment is not pending', 'csrf_hash' => csrf_hash()]);
        }

        try {
            // Reject the assignment - clear pending fields
            $this->courseModel->update($courseId, [
                'pending_teacher_id' => null,
                'teacher_assignment_status' => 'rejected',
                'teacher_assignment_requested_at' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Notify all admins
            $notificationModel = new \App\Models\NotificationModel();
            $courseTitle = $course['title'] ?? 'a course';
            $userModel = new \App\Models\UserModel();
            $teacher = $userModel->find($teacherId);
            $teacherName = $teacher['name'] ?? 'Teacher';
            
            // Get all admin users
            $admins = $userModel->where('role', 'admin')
                               ->select('id')
                               ->findAll();
            
            // Send notification to all admins
            if (!empty($admins)) {
                foreach ($admins as $admin) {
                    $notificationModel->add((int)$admin['id'], '❌ Teacher "' . esc($teacherName) . '" has rejected assignment to teach the course: ' . esc($courseTitle) . '.');
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Assignment rejected successfully',
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to reject assignment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Admin: Get all students for enrollment selection
     */
    public function getAllStudents()
    {
        $session = session();
        
        // Security: only admin can view all students
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userModel = new \App\Models\UserModel();
        $students = $userModel->where('role', 'student')
                            ->select('id, name, email')
                            ->orderBy('name', 'ASC')
                            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'students' => $students,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Admin: Get all teachers for assignment selection
     */
    public function getAllTeachers()
    {
        $session = session();
        
        // Security: only admin can view all teachers
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $userModel = new \App\Models\UserModel();
        $teachers = $userModel->where('role', 'teacher')
                            ->select('id, name, email')
                            ->orderBy('name', 'ASC')
                            ->findAll();

        return $this->response->setJSON([
            'success' => true,
            'teachers' => $teachers,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Get semesters by academic year (AJAX)
     */
    public function getSemestersByAcademicYear()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $acadYearId = (int) $this->request->getPost('acad_year_id') ?? $this->request->getGet('acad_year_id');
        
        if ($acadYearId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid academic year ID', 'csrf_hash' => csrf_hash()]);
        }

        $semesterModel = new SemesterModel();
        $semesters = $semesterModel->getSemestersByAcademicYear($acadYearId);

        return $this->response->setJSON([
            'success' => true,
            'semesters' => $semesters,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Get terms by semester (AJAX)
     */
    public function getTermsBySemester()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $semesterId = (int) $this->request->getPost('semester_id') ?? $this->request->getGet('semester_id');
        
        if ($semesterId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid semester ID', 'csrf_hash' => csrf_hash()]);
        }

        $termModel = new TermModel();
        $terms = $termModel->getTermsBySemester($semesterId);

        return $this->response->setJSON([
            'success' => true,
            'terms' => $terms,
            'csrf_hash' => csrf_hash()
        ]);
    }

    /**
     * Teacher: Approve enrollment request
     */
    public function teacherApproveEnrollment()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $teacherId = $session->get('userID');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        // Get enrollment details
        $enrollment = $this->enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Enrollment not found', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course belongs to this teacher
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course || $course['teacher_id'] != $teacherId) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $updated = $this->enrollmentModel->approveByTeacher($enrollmentId, $enrollment['course_id'], $teacherId);
            
            if ($updated) {
                // Get student info
                $userModel = new \App\Models\UserModel();
                $student = $userModel->find($enrollment['user_id']);
                
                // Notify student
                $notif = new \App\Models\NotificationModel();
                $notif->add($enrollment['user_id'], 'Teacher approved your enrollment in "' . ($course['title'] ?? '') . '". You are now enrolled!');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment approved by teacher',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Failed to approve enrollment', 'csrf_hash' => csrf_hash()]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to approve enrollment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Teacher: Reject enrollment request
     */
    public function teacherRejectEnrollment()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'teacher') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');
        $teacherId = $session->get('userID');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        // Get enrollment details
        $enrollment = $this->enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Enrollment not found', 'csrf_hash' => csrf_hash()]);
        }

        // Verify course belongs to this teacher
        $course = $this->courseModel->find($enrollment['course_id']);
        if (!$course || $course['teacher_id'] != $teacherId) {
            return $this->response->setStatusCode(403)
                ->setJSON(['success' => false, 'message' => 'Access denied', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $updated = $this->enrollmentModel->rejectByTeacher($enrollmentId, $enrollment['course_id'], $teacherId);
            
            if ($updated) {
                // Notify student
                $notif = new \App\Models\NotificationModel();
                $notif->add($enrollment['user_id'], 'Your enrollment request for "' . ($course['title'] ?? '') . '" was rejected by the teacher.');

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment rejected by teacher',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Failed to reject enrollment', 'csrf_hash' => csrf_hash()]);
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
     * Admin: Approve enrollment request (after teacher approval)
     */
    public function adminApproveEnrollment()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        // Get enrollment details
        $enrollment = $this->enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Enrollment not found', 'csrf_hash' => csrf_hash()]);
        }

        // Admin can approve even if teacher hasn't approved yet (admin bypass)

        try {
            $updated = $this->enrollmentModel->approveByAdmin($enrollmentId);
            
            if ($updated) {
                // Get course and student info
                $course = $this->courseModel->find($enrollment['course_id']);
                $userModel = new \App\Models\UserModel();
                $student = $userModel->find($enrollment['user_id']);
                
                // Notify student
                $notif = new \App\Models\NotificationModel();
                $notif->add($enrollment['user_id'], 'Admin approved your enrollment in "' . ($course['title'] ?? '') . '". You are now enrolled!');
                
                // Notify teacher (if not already approved by teacher)
                if (!empty($course['teacher_id']) && $enrollment['teacher_approved'] != 1) {
                    $notif->add((int)$course['teacher_id'], 'Admin directly enrolled "' . ($student['name'] ?? 'Unknown') . '" in your course "' . ($course['title'] ?? '') . '".');
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment approved by admin',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Failed to approve enrollment', 'csrf_hash' => csrf_hash()]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => 'Failed to approve enrollment: ' . $e->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    /**
     * Admin: Reject enrollment request
     */
    public function adminRejectEnrollment()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollmentId = (int) $this->request->getPost('enrollment_id');

        if ($enrollmentId <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid enrollment ID', 'csrf_hash' => csrf_hash()]);
        }

        // Get enrollment details
        $enrollment = $this->enrollmentModel->find($enrollmentId);
        if (!$enrollment) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Enrollment not found', 'csrf_hash' => csrf_hash()]);
        }

        try {
            $updated = $this->enrollmentModel->rejectByAdmin($enrollmentId);
            
            if ($updated) {
                // Get course and student info
                $course = $this->courseModel->find($enrollment['course_id']);
                $userModel = new \App\Models\UserModel();
                $student = $userModel->find($enrollment['user_id']);
                
                // Notify student
                $notif = new \App\Models\NotificationModel();
                $notif->add($enrollment['user_id'], 'Your enrollment request for "' . ($course['title'] ?? '') . '" was rejected by admin.');
                
                // Notify teacher
                if (!empty($course['teacher_id'])) {
                    $notif->add((int)$course['teacher_id'], 'Admin rejected enrollment for "' . ($student['name'] ?? 'Unknown') . '" in your course "' . ($course['title'] ?? '') . '".');
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Enrollment rejected by admin',
                    'csrf_hash' => csrf_hash()
                ]);
            } else {
                return $this->response->setStatusCode(400)
                    ->setJSON(['success' => false, 'message' => 'Failed to reject enrollment', 'csrf_hash' => csrf_hash()]);
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
     * Get pending enrollments for admin (waiting for admin approval after teacher approval)
     */
    public function getPendingAdminApprovals()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'Unauthorized', 'csrf_hash' => csrf_hash()]);
        }

        $enrollments = $this->enrollmentModel->getPendingAdminApprovals();

        return $this->response->setJSON([
            'success' => true,
            'enrollments' => $enrollments,
            'total' => count($enrollments),
            'csrf_hash' => csrf_hash()
        ]);
    }
}
