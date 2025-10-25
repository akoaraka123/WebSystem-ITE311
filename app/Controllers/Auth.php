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

    // ✅ Redirect kung hindi naka-login
    if (!$session->get('isLoggedIn')) {
        return redirect()->to(base_url('login'));
    }

    // 🔹 Get user info from session
    $role   = $session->get('role');
    $userID = $session->get('userID');

    // 🔹 Load models
    $userModel       = new \App\Models\UserModel();
    $courseModel     = new \App\Models\CourseModel();
    $enrollmentModel = new \App\Models\EnrollmentModel();
    $materialModel   = new \App\Models\MaterialModel();

    // 🔹 Base data
    $data = [
        'user' => [
            'id'    => $userID,
            'name'  => $session->get('name'),
            'email' => $session->get('email'),
            'role'  => $role
        ],
        'flash' => [
            'success' => $session->getFlashdata('success'),
            'error'   => $session->getFlashdata('error')
        ],
        'totalUsers'   => null,
        'totalCourses' => null,
        'myCourses'    => [],
        'enrolled'     => [],
        'available'    => [],
        'materials'    => []
    ];

    // ==============================
    // ROLE-BASED CONTENT LOGIC
    // ==============================
    switch ($role) {

        case 'admin':
            // Admin: show overview only
            $data['totalUsers']   = $userModel->countAll();
            $data['totalCourses'] = $courseModel->countAll();
            break;

        case 'teacher':
            // Teacher: show only courses assigned to them
            $myCourses = $courseModel->where('teacher_id', $userID)->findAll();

            foreach ($myCourses as &$course) {
                // Load materials for each course
                $course['materials'] = $materialModel->getMaterialsByCourse($course['id']);
            }

            $data['myCourses'] = $myCourses;
            break;

        case 'student':
            // Student: show enrolled courses
            $enrolled = $enrollmentModel->getUserEnrollments($userID);
            $enrolledIDs = array_column($enrolled, 'id');

            // Available courses: not yet enrolled
            $available = !empty($enrolledIDs)
                ? $courseModel->whereNotIn('id', $enrolledIDs)->findAll()
                : $courseModel->findAll();

            // Materials for each enrolled course
            $materials = [];
            foreach ($enrolled as $course) {
                $materials[$course['id']] = $materialModel->getMaterialsByCourse($course['id']);
            }

            $data['enrolled']  = $enrolled;
            $data['available'] = $available;
            $data['materials'] = $materials;
            break;

        default:
            return redirect()->to(base_url('login'));
    }

    // ✅ Render dashboard for all roles
    return view('auth/dashboard', $data);
}






public function enroll($course_id)
{
    $session = \Config\Services::session();

    // Security: redirect kung hindi naka-login o hindi student
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }
        return redirect()->to(base_url('login'));
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
    $enrollmentModel->insert([
        'user_id'         => $userID,
        'course_id'       => $course_id,
        'enrollment_date' => date('Y-m-d H:i:s')
    ]);

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
