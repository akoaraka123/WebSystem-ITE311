<?php

namespace App\Controllers;

class Auth extends BaseController
{
    
    // 1) REGISTER
    public function register()
    {
        $session    = \Config\Services::session();
        $validation = \Config\Services::validation();
        $db         = \Config\Database::connect();

        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name'             => 'required|min_length[3]|max_length[100]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'role'             => 'required|in_list[student,teacher]',
                'password'         => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]',
                'password_confirm' => 'required|matches[password]'
            ];
            
            // Custom validation messages for password strength
            $messages = [
                'password' => [
                    'min_length' => 'Password must be at least 8 characters long.',
                    'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
                ]
            ];
            
            if (!$this->validate($rules, $messages)) {
                $session->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }
            
            // If validation passes, proceed with registration
            $db->table('users')->insert([
                'name'       => $this->request->getPost('name'),
                'email'      => $this->request->getPost('email'),
                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'       => $this->request->getPost('role') ?: 'user', // use role from form or default
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $session->setFlashdata('success', 'Registration successful! Please login.');
            return redirect()->to(base_url('login'));
        }

        return view('auth/register');
    }

// 2) LOGIN
public function login()
{
    $session = \Config\Services::session();
    $db      = \Config\Database::connect();

    // Redirect if already logged in
    if ($session->get('isLoggedIn')) {
        return redirect()->to(base_url('dashboard'));
    }

    if ($this->request->getMethod() === 'POST') {
        $rules = [
            'login'    => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $session->setFlashdata('error', 'Please enter both login and password.');
            return redirect()->back()->withInput();
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // Use UserModel to respect soft deletes
        $userModel = new \App\Models\UserModel();
        
        // Get user by email or name (UserModel automatically excludes soft-deleted users)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = $userModel->where('email', $login)->first();
        } else {
            $user = $userModel->where('name', $login)->first();
        }

        if (!$user) {
            $session->setFlashdata('error', 'Account not found.');
            return redirect()->back()->withInput();
        }
        
        // Additional check: verify user is not soft-deleted (double check)
        if (!empty($user['deleted_at'])) {
            $session->setFlashdata('error', 'This account has been deleted.');
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            // Track failed login attempts
            $cache = \Config\Services::cache();
            $ipAddress = $this->request->getIPAddress();
            $key = 'login_attempts_' . md5($ipAddress);
            $attempts = $cache->get($key) ?? 0;
            $attempts++;
            
            // Store attempts for 15 minutes
            $cache->save($key, $attempts, 900);
            
            // Lock account after 5 failed attempts
            if ($attempts >= 5) {
                $lockoutKey = 'login_lockout_' . md5($ipAddress);
                $lockoutCountKey = 'login_lockout_count_' . md5($ipAddress);
                
                // Get current lockout count (how many times they've been locked out)
                $lockoutCount = $cache->get($lockoutCountKey) ?? 0;
                $lockoutCount++;
                
                // Progressive lockout: first time = 5 minutes, second time = 10 minutes
                if ($lockoutCount == 1) {
                    $lockoutDuration = 300; // 5 minutes = 300 seconds
                    $lockoutMinutes = 5;
                } else {
                    $lockoutDuration = 600; // 10 minutes = 600 seconds
                    $lockoutMinutes = 10;
                }
                
                // Save lockout time and count
                $cache->save($lockoutKey, time() + $lockoutDuration, $lockoutDuration);
                $cache->save($lockoutCountKey, $lockoutCount, 900); // Keep count for 15 minutes
                
                $session->setFlashdata('error', "Too many failed login attempts. Please try again in {$lockoutMinutes} minute(s).");
            } else {
                $remaining = 5 - $attempts;
                $session->setFlashdata('error', 'Incorrect password. ' . $remaining . ' attempt(s) remaining.');
            }
            return redirect()->back()->withInput();
        }

        // Clear failed attempts on successful login
        $cache = \Config\Services::cache();
        $ipAddress = $this->request->getIPAddress();
        $key = 'login_attempts_' . md5($ipAddress);
        $cache->delete($key);
        $lockoutKey = 'login_lockout_' . md5($ipAddress);
        $cache->delete($lockoutKey);
        $lockoutCountKey = 'login_lockout_count_' . md5($ipAddress);
        $cache->delete($lockoutCountKey);

        // Regenerate session ID for security (prevent session fixation)
        $session->regenerate(true);

        // Set session
        $session->set([
            'userID'       => $user['id'],
            'name'         => $user['name'],
            'email'        => $user['email'],
            'role'         => $user['role'],
            'isLoggedIn'   => true,
            'last_activity' => time() // Track last activity for session timeout
        ]);
        
        $session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');

        // Redirect to unified dashboard
        return redirect()->to('/dashboard');
    }

    return view('auth/login');
}

    // 3) LOGOUT
    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to(base_url('login'));
    }

    // 4) FORGOT PASSWORD
    public function forgotPassword()
    {
        $session = \Config\Services::session();
        
        // Redirect if already logged in
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email' => 'required|valid_email'
            ];

            if (!$this->validate($rules)) {
                $session->setFlashdata('error', 'Please enter a valid email address.');
                return redirect()->back()->withInput();
            }

            $email = $this->request->getPost('email');
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('email', $email)->first();

            // Always show success message for security (don't reveal if email exists)
            if ($user) {
                // Generate reset token
                $resetToken = bin2hex(random_bytes(32));
                $resetTokenExpires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save token to database
                $userModel->update($user['id'], [
                    'reset_token' => $resetToken,
                    'reset_token_expires' => $resetTokenExpires
                ]);

                // Send reset email
                $emailService = \Config\Services::email();
                $resetLink = base_url('reset-password/' . $resetToken);
                
                $emailService->setFrom('noreply@lms.com', 'LMS System');
                $emailService->setTo($email);
                $emailService->setSubject('Password Reset Request');
                $emailService->setMessage('Hello ' . $user['name'] . ',

You requested to reset your password. Click the link below to reset your password:

' . $resetLink . '

This link will expire in 1 hour.

If you did not request this password reset, please ignore this email.

Best regards,
LMS Team');

                // Try to send email (don't fail if email sending fails)
                try {
                    $emailService->send();
                } catch (\Exception $e) {
                    log_message('error', 'Failed to send password reset email: ' . $e->getMessage());
                }
            }

            $session->setFlashdata('success', 'If an account with that email exists, a password reset link has been sent.');
            return redirect()->to(base_url('forgot-password'));
        }

        return view('auth/forgot_password');
    }

    // 5) RESET PASSWORD
    public function resetPassword($token = null)
    {
        $session = \Config\Services::session();
        
        // Redirect if already logged in
        if ($session->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }

        // Get token from URL or POST
        if (!$token) {
            $token = $this->request->getPost('token');
        }

        if (!$token) {
            $session->setFlashdata('error', 'Invalid reset token.');
            return redirect()->to(base_url('forgot-password'));
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('reset_token', $token)->first();

        // Check if token exists and is not expired
        if (!$user) {
            $session->setFlashdata('error', 'Invalid or expired reset token.');
            return redirect()->to(base_url('forgot-password'));
        }

        if (empty($user['reset_token_expires']) || strtotime($user['reset_token_expires']) < time()) {
            $session->setFlashdata('error', 'Reset token has expired. Please request a new one.');
            // Clear expired token
            $userModel->update($user['id'], [
                'reset_token' => null,
                'reset_token_expires' => null
            ]);
            return redirect()->to(base_url('forgot-password'));
        }

        // Handle password reset form submission
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/]',
                'password_confirm' => 'required|matches[password]'
            ];

            $messages = [
                'password' => [
                    'min_length' => 'Password must be at least 8 characters long.',
                    'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
                ],
                'password_confirm' => [
                    'matches' => 'Password confirmation does not match.'
                ]
            ];

            if (!$this->validate($rules, $messages)) {
                $session->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back()->withInput();
            }

            // Update password and clear reset token
            $newPassword = $this->request->getPost('password');
            $userModel->update($user['id'], [
                'password' => $newPassword, // UserModel will hash it automatically
                'reset_token' => null,
                'reset_token_expires' => null
            ]);

            $session->setFlashdata('success', 'Your password has been reset successfully. Please login with your new password.');
            return redirect()->to(base_url('login'));
        }

        // Show reset password form
        $data = ['token' => $token];
        return view('auth/reset_password', $data);
    }

// ================================
// DASHBOARD 
// ================================
public function dashboard()
{
    $session = \Config\Services::session();

    // Redirect kung hindi naka-login
    if (!$session->get('isLoggedIn')) {
        return redirect()->to(base_url('login'));
    }

    // 🔹 Get user info from session
    $role   = $session->get('role');
    $userID = $session->get('userID');
        'userID' => $userID,
        'name' => $session->get('name'),
        'email' => $session->get('email')
    ]));

    // Load models
    $userModel       = new \App\Models\UserModel();
    $courseModel     = new \App\Models\CourseModel();
    $enrollmentModel = new \App\Models\EnrollmentModel();
    $materialModel   = new \App\Models\MaterialModel();

    // Base data - initialize all variables to prevent undefined errors
    $data = [
        'user' => [
            'id'    => $userID,
            'name'  => $session->get('name'),
            'email' => $session->get('email'),
            'role'  => $role,
            'created_at' => $session->get('created_at'),
            'last_login' => $session->get('last_login')
        ],
        'flash' => [
            'success' => $session->getFlashdata('success'),
            'error'   => $session->getFlashdata('error')
        ],
        'totalUsers'   => 0,
        'totalCourses' => 0,
        'myCourses'    => [],
        'enrolled'     => [],
        'available'    => [],
        'materials'    => [],
        'enrollments'  => [],
        'recentUploads' => []
    ];

    // ROLE-BASED CONTENT LOGIC
    switch ($role) {

        case 'admin':
            // Admin: show overview and recent uploads
            $data['totalUsers']   = $userModel->countAll();
            $data['totalCourses'] = $courseModel->countAll();
            
            // Get user counts by role
            $data['totalStudents'] = $userModel->where('role', 'student')->countAllResults();
            $data['totalTeachers'] = $userModel->where('role', 'teacher')->countAllResults();
            $data['totalAdmins'] = $userModel->where('role', 'admin')->countAllResults();
            
            // Get enrollment count
            $data['totalEnrollments'] = $enrollmentModel->countAllResults();
            
            // Get active school settings
            $schoolSettingsModel = new \App\Models\SchoolSettingsModel();
            $data['activeSchoolSettings'] = $schoolSettingsModel->getActiveSettings();
            
            // Get programs count
            $programModel = new \App\Models\ProgramModel();
            $data['totalPrograms'] = $programModel->where('is_active', 1)->countAllResults();
            
            // Get all courses grouped by program for admin dashboard
            $allCourses = $courseModel->getCoursesWithAcademicInfo();
            $groupedCoursesByProgram = [];
            
            foreach ($allCourses as $course) {
                $programId = $course['program_id'] ?? null;
                $programKey = $programId ? $programId : 'no_program';
                $programName = !empty($course['program_code']) 
                    ? $course['program_code'] . ' - ' . $course['program_name'] 
                    : 'No Program Assigned';
                
                if (!isset($groupedCoursesByProgram[$programKey])) {
                    $groupedCoursesByProgram[$programKey] = [
                        'program_id' => $programId,
                        'program_name' => $programName,
                        'program_code' => $course['program_code'] ?? '',
                        'courses' => []
                    ];
                }
                
                $groupedCoursesByProgram[$programKey]['courses'][] = $course;
            }
            
            // Sort programs (No Program Assigned last)
            uksort($groupedCoursesByProgram, function($a, $b) use ($groupedCoursesByProgram) {
                if ($a === 'no_program') return 1;
                if ($b === 'no_program') return -1;
                return strcmp($groupedCoursesByProgram[$a]['program_code'] ?? '', $groupedCoursesByProgram[$b]['program_code'] ?? '');
            });
            
            $data['groupedCoursesByProgram'] = $groupedCoursesByProgram;
            
            // Initialize recentUploads as empty array first
            $data['recentUploads'] = [];
            
            // Get recent uploads with error handling
            try {
                $uploads = $materialModel->select('materials.*, COALESCE(users.name, "Unknown Teacher") as teacher_name, COALESCE(users.email, "unknown@example.com") as teacher_email, COALESCE(courses.title, "Untitled Course") as course_title')
                    ->join('courses', 'courses.id = materials.course_id', 'left')
                    ->join('users', 'users.id = courses.teacher_id', 'left')
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll(10);
                
                // Only set recentUploads if we got results
                if ($uploads) {
                    $data['recentUploads'] = $uploads;
                }
            } catch (\Exception $e) {
                // If there's an error, keep recentUploads as empty array
                log_message('error', 'Error fetching recent uploads: ' . $e->getMessage());
            }
            break;

        case 'teacher':
            // Teacher: show only courses assigned to them with full academic info
            $myCourses = $courseModel->select('courses.*, 
                            academic_years.display_name as acad_year_name,
                            semesters.name as semester_name,
                            terms.term_name,
                            programs.code as program_code,
                            programs.name as program_name,
                            courses.schedule_time_start,
                            courses.schedule_time_end,
                            courses.schedule_date_start,
                            courses.schedule_date_end,
                            courses.schedule_date,
                            courses.course_number,
                            courses.duration,
                            users.name as teacher_name,
                            users.id as teacher_user_id')
                        ->join('academic_years', 'academic_years.id = courses.acad_year_id', 'left')
                        ->join('semesters', 'semesters.id = courses.semester_id', 'left')
                        ->join('terms', 'terms.id = courses.term_id', 'left')
                        ->join('programs', 'programs.id = courses.program_id', 'left')
                        ->join('users', 'users.id = courses.teacher_id', 'left')
                        ->where('courses.teacher_id', $userID)
                        ->findAll();
            $enrollments = [];
            $enrollmentStats = []; // For detailed stats (accepted, pending)
            $materials = [];

            foreach ($myCourses as $course) {
                $courseId = $course['id'];
                
                // Get total enrollment count (for backward compatibility)
                $enrollments[$courseId] = $enrollmentModel->where('course_id', $courseId)->countAllResults();
                
                // Get detailed enrollment statistics
                $enrollmentStats[$courseId] = [
                    'accepted' => $enrollmentModel->where('course_id', $courseId)
                                                 ->where('status', 'accepted')
                                                 ->where('teacher_approved', 1)
                                                 ->countAllResults(),
                    'pending' => $enrollmentModel->where('course_id', $courseId)
                                                ->where('status', 'pending')
                                                ->where('teacher_approved', 0)
                                                ->countAllResults(),
                    'total' => $enrollments[$courseId]
                ];
                
                // Load materials for each course
                $materials[$courseId] = $materialModel->getMaterialsByCourse($courseId);
            }

            // Get all pending enrollment requests for this teacher
            $data['pending_enrollments'] = $enrollmentModel->getPendingEnrollmentsForTeacher($userID);

            // Get unique academic years and semesters from courses for filter dropdowns
            $uniqueAcademicYears = [];
            $uniqueSemesters = [];
            foreach ($myCourses as $course) {
                if (!empty($course['acad_year_name']) && !in_array($course['acad_year_name'], $uniqueAcademicYears)) {
                    $uniqueAcademicYears[] = $course['acad_year_name'];
                }
                if (!empty($course['semester_name']) && !in_array($course['semester_name'], $uniqueSemesters)) {
                    $uniqueSemesters[] = $course['semester_name'];
                }
            }
            sort($uniqueAcademicYears);
            sort($uniqueSemesters);
            
            $data['myCourses'] = $myCourses;
            $data['enrollments'] = $enrollments;
            $data['enrollmentStats'] = $enrollmentStats; // Add detailed stats
            $data['materials'] = $materials;
            $data['uniqueAcademicYears'] = $uniqueAcademicYears; // For teacher dashboard filter
            $data['uniqueSemesters'] = $uniqueSemesters; // For teacher dashboard filter
            break;

        case 'student':
            // Student: show enrolled courses
            $data['enrolled'] = $enrollmentModel->getUserEnrollments($userID);
            $enrolledIDs = array_column($data['enrolled'], 'course_id');

            // Get pending enrollment requests
            $data['pending_enrollments'] = $enrollmentModel->getPendingEnrollments($userID);
            
            // Students should only see enrolled courses, not available courses
            $data['available'] = [];

            // Materials for each enrolled course
            $data['materials'] = [];
            foreach ($data['enrolled'] as $enrollment) {
                $data['materials'][$enrollment['course_id']] = $materialModel->getMaterialsByCourse($enrollment['course_id']);
            }
            break;

        default:
            return redirect()->to(base_url('login'));
    }

    // ✅ Render dashboard for all roles
    return view('auth/dashboard', $data);
}






public function enroll($course_id = null)
{
    $session = \Config\Services::session();
    
    // Get course_id from URL parameter or POST data
    if ($course_id === null) {
        $course_id = $this->request->getPost('course_id');
    }
    

    // Security: redirect kung hindi naka-login o hindi student
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return redirect()->to(base_url('login'));
    }
    
    // Check if course_id is valid
    if (!$course_id || !is_numeric($course_id)) {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['success' => false, 'message' => 'Invalid course ID']);
        }
        $session->setFlashdata('error', 'Invalid course ID');
        return redirect()->to(base_url('auth/dashboard'));
    }

    $userID = $session->get('userID');

    // Load Enrollment Model
    $enrollmentModel = new \App\Models\EnrollmentModel();

    // Check kung naka-enroll na
    if ($enrollmentModel->isAlreadyEnrolled($userID, $course_id)) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ]);
        }
        $session->setFlashdata('success', 'You are already enrolled in this course.');
        return redirect()->to(base_url('auth/dashboard'));
    }

    // Enroll user sa course
    try {
        $enrollmentModel->insert([
            'user_id'         => $userID,
            'course_id'       => $course_id,
            'enrollment_date' => date('Y-m-d H:i:s')
        ]);
        
        
        // Create notifications
        $notif = new \App\Models\NotificationModel();
        
        // Get course details for notification
        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->find($course_id);
        
        if ($course) {
            // Notify the student
            $notif->add($userID, 'You enrolled in: ' . $course['title']);
            
            // Notify the course teacher
            if (!empty($course['teacher_id'])) {
                $notif->add((int)$course['teacher_id'], 'A student enrolled in your course: ' . $course['title']);
            }
            
        }
        
    } catch (\Exception $e) {
        log_message('error', 'Enrollment failed: ' . $e->getMessage());
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Enrollment failed: ' . $e->getMessage()
            ]);
        }
        
        $session->setFlashdata('error', 'Enrollment failed. Please try again.');
        return redirect()->to(base_url('auth/dashboard'));
    }

    // Return response depende sa request type
    if ($this->request->isAJAX()) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'You have successfully enrolled in the course!'
        ]);
    }

    $session->setFlashdata('success', 'You have successfully enrolled in the course!');
    return redirect()->to(base_url('auth/dashboard'));
}

public function unenroll($course_id = null)
{
    $session = \Config\Services::session();
    
    // Get course_id from URL parameter or POST data
    if ($course_id === null) {
        $course_id = $this->request->getPost('course_id');
    }
    

    // Security: redirect kung hindi naka-login o hindi student
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return redirect()->to(base_url('login'));
    }
    
    // Check if course_id is valid
    if (!$course_id || !is_numeric($course_id)) {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['success' => false, 'message' => 'Invalid course ID']);
        }
        $session->setFlashdata('error', 'Invalid course ID');
        return redirect()->to(base_url('auth/dashboard'));
    }

    $userID = $session->get('userID');

    // Load Enrollment Model
    $enrollmentModel = new \App\Models\EnrollmentModel();

    // Check kung naka-enroll
    if (!$enrollmentModel->isAlreadyEnrolled($userID, $course_id)) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are not enrolled in this course.'
            ]);
        }
        $session->setFlashdata('error', 'You are not enrolled in this course.');
        return redirect()->to(base_url('auth/dashboard'));
    }

    // Unenroll user sa course
    try {
        $enrollmentModel->unenroll($userID, $course_id);
        
        
        // Create notifications
        $notif = new \App\Models\NotificationModel();
        
        // Get course details for notification
        $courseModel = new \App\Models\CourseModel();
        $course = $courseModel->find($course_id);
        
        if ($course) {
            // Notify the student
            $notif->add($userID, 'You unenrolled from: ' . $course['title']);
            
            // Notify the course teacher
            if (!empty($course['teacher_id'])) {
                $notif->add((int)$course['teacher_id'], 'A student unenrolled from your course: ' . $course['title']);
            }
            
        }
        
    } catch (\Exception $e) {
        log_message('error', 'Unenrollment failed: ' . $e->getMessage());
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unenrollment failed: ' . $e->getMessage()
            ]);
        }
        
        $session->setFlashdata('error', 'Unenrollment failed. Please try again.');
        return redirect()->to(base_url('auth/dashboard'));
    }

    // Return response depende sa request type
    if ($this->request->isAJAX()) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'You have successfully unenrolled from the course!'
        ]);
    }

    $session->setFlashdata('success', 'You have successfully unenrolled from the course!');
    return redirect()->to(base_url('auth/dashboard'));
}

    


}
