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
                $cache->save($lockoutKey, time() + 900, 900); // 15 minutes lockout
                $session->setFlashdata('error', 'Too many failed login attempts. Please try again in 15 minutes.');
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
        
        // Debug: log session set
        log_message('debug', 'Session set for user: ' . json_encode([
            'userID' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role']
        ]));

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
        log_message('debug', 'User not logged in, redirecting to login');
        return redirect()->to(base_url('login'));
    }

    // 🔹 Get user info from session
    $role   = $session->get('role');
    $userID = $session->get('userID');
    
    // Debug: log session data
    log_message('debug', 'Session data: ' . json_encode([
        'isLoggedIn' => $session->get('isLoggedIn'),
        'role' => $role,
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
    log_message('debug', 'User role: ' . $role);
    switch ($role) {

        case 'admin':
            // Admin: show overview and recent uploads
            $data['totalUsers']   = $userModel->countAll();
            $data['totalCourses'] = $courseModel->countAll();
            
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
            // Teacher: show only courses assigned to them
            $myCourses = $courseModel->where('teacher_id', $userID)->findAll();
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
                                                 ->groupStart()
                                                     ->where('status', 'accepted')
                                                     ->orWhere('status IS NULL')
                                                 ->groupEnd()
                                                 ->countAllResults(),
                    'pending' => $enrollmentModel->where('course_id', $courseId)
                                                ->where('status', 'pending')
                                                ->countAllResults(),
                    'total' => $enrollments[$courseId]
                ];
                
                // Load materials for each course
                $materials[$courseId] = $materialModel->getMaterialsByCourse($courseId);
            }

            $data['myCourses'] = $myCourses;
            $data['enrollments'] = $enrollments;
            $data['enrollmentStats'] = $enrollmentStats; // Add detailed stats
            $data['materials'] = $materials;
            break;

        case 'student':
            // Student: show enrolled courses
            log_message('debug', 'Fetching enrollments for userID: ' . $userID);
            $data['enrolled'] = $enrollmentModel->getUserEnrollments($userID);
            $enrolledIDs = array_column($data['enrolled'], 'course_id');
            log_message('debug', 'Enrolled courses count: ' . count($data['enrolled']));

            // Get pending enrollment requests
            $data['pending_enrollments'] = $enrollmentModel->getPendingEnrollments($userID);
            $pendingIDs = array_column($data['pending_enrollments'], 'course_id');
            
            // Combine enrolled and pending IDs to exclude from available courses
            $allEnrolledIDs = array_merge($enrolledIDs, $pendingIDs);

            // Available courses: not yet enrolled or pending
            $data['available'] = [];
            try {
                // First, let's get all courses to see if any exist
                $allCourses = $courseModel->select('id, title, description')->findAll();
                log_message('debug', 'All courses in database: ' . json_encode($allCourses));
                
                // Test: let's try a simple query without joins
                $testQuery = $courseModel->findAll();
                log_message('debug', 'Simple findAll result: ' . json_encode($testQuery));
                
                if (!empty($allEnrolledIDs)) {
                    $data['available'] = $courseModel->select('id, title, description')
                                                ->whereNotIn('id', $allEnrolledIDs)
                                                ->findAll();
                } else {
                    $data['available'] = $allCourses;
                }
                // Debug: log the available courses
                log_message('debug', 'Available courses: ' . json_encode($data['available']));
                log_message('debug', 'Enrolled IDs: ' . json_encode($enrolledIDs));
            } catch (\Exception $e) {
                // If there's an error, keep available as empty array
                log_message('error', 'Error fetching available courses: ' . $e->getMessage());
                $data['available'] = [];
            }

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
    // Debug: log what we're passing to the view
    log_message('debug', 'Passing to view: ' . json_encode([
        'user_id' => $data['user']['id'] ?? 'N/A',
        'user_role' => $data['user']['role'] ?? 'N/A',
        'enrolled_count' => count($data['enrolled'] ?? []),
        'available_count' => count($data['available'] ?? [])
    ]));
    return view('auth/dashboard', $data);
}






public function enroll($course_id = null)
{
    $session = \Config\Services::session();
    
    // Get course_id from URL parameter or POST data
    if ($course_id === null) {
        $course_id = $this->request->getPost('course_id');
    }
    
    // Debug: log the course_id
    log_message('debug', 'Enrollment attempt for course_id: ' . $course_id);

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
        
        log_message('debug', 'Enrollment successful for user_id: ' . $userID . ', course_id: ' . $course_id);
        
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
            
            log_message('debug', 'Notifications created for enrollment');
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
    
    // Debug: log the course_id
    log_message('debug', 'Unenrollment attempt for course_id: ' . $course_id);

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
        
        log_message('debug', 'Unenrollment successful for user_id: ' . $userID . ', course_id: ' . $course_id);
        
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
            
            log_message('debug', 'Notifications created for unenrollment');
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
