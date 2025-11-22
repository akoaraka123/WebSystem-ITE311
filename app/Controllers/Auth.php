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
                'password'         => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]'
            ];

            if ($this->validate($rules)) {
                $db->table('users')->insert([
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role'       => 'user', // default role
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $session->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to(base_url('login'));
            }

            $session->setFlashdata('errors', $validation->getErrors());
            return redirect()->back()->withInput();
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

        // Get user by email or name
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = $db->table('users')->where('email', $login)->get()->getRowArray();
        } else {
            $user = $db->table('users')->where('name', $login)->get()->getRowArray();
        }

        if (!$user) {
            $session->setFlashdata('error', 'Account not found.');
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // Set session
        $session->set([
            'userID'     => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
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
            $materials = [];

            foreach ($myCourses as $course) {
                // Get enrollment count for each course
                $enrollments[$course['id']] = $enrollmentModel->where('course_id', $course['id'])->countAllResults();
                // Load materials for each course
                $materials[$course['id']] = $materialModel->getMaterialsByCourse($course['id']);
            }

            $data['myCourses'] = $myCourses;
            $data['enrollments'] = $enrollments;
            $data['materials'] = $materials;
            break;

        case 'student':
            // Student: show enrolled courses
            log_message('debug', 'Fetching enrollments for userID: ' . $userID);
            $data['enrolled'] = $enrollmentModel->getUserEnrollments($userID);
            $enrolledIDs = array_column($data['enrolled'], 'course_id');
            log_message('debug', 'Enrolled courses count: ' . count($data['enrolled']));

            // Available courses: not yet enrolled
            $data['available'] = [];
            try {
                // First, let's get all courses to see if any exist
                $allCourses = $courseModel->select('id, title, description')->findAll();
                log_message('debug', 'All courses in database: ' . json_encode($allCourses));
                
                // Test: let's try a simple query without joins
                $testQuery = $courseModel->findAll();
                log_message('debug', 'Simple findAll result: ' . json_encode($testQuery));
                
                if (!empty($enrolledIDs)) {
                    $data['available'] = $courseModel->select('id, title, description')
                                                ->whereNotIn('id', $enrolledIDs)
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

    


}
