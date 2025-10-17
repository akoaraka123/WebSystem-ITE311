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

    // 🔹 If already logged in, redirect based on role
    if ($session->get('isLoggedIn')) {
        $role = $session->get('role');
        return $this->redirectByRole($role);
    }

    // 🔹 Handle form submission
    if ($this->request->getMethod() === 'POST') {

        // Validation rules
        $rules = [
            'login'    => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $session->setFlashdata('error', 'Please enter both login and password.');
            return redirect()->back()->withInput();
        }

        // Get login credentials
        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // 🔹 Identify if email or name is used for login
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = $db->table('users')->where('email', $login)->get()->getRowArray();
        } else {
            $user = $db->table('users')->where('name', $login)->get()->getRowArray();
        }

        // 🔹 Check if user exists
        if (!$user) {
            $session->setFlashdata('error', 'Account not found.');
            return redirect()->back()->withInput();
        }

        // 🔹 Verify password
        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // 🔹 Set session data
        $session->set([
            'userID'     => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        $session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');

        // 🔹 Redirect based on role
        return $this->redirectByRole($user['role']);
    }

    // 🔹 Display login form
    return view('auth/login');
}

/**
 *  Helper function for role-based redirection
 */
private function redirectByRole($role)
{
    switch ($role) {
        case 'student':
            return redirect()->to('/announcements');
        case 'teacher':
            return redirect()->to('/teacher/dashboard');
        case 'admin':
            return redirect()->to('/admin/dashboard');
        default:
            return redirect()->to('/dashboard');
    }
}


    // 3) LOGOUT
    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        return redirect()->to(base_url('/login'));
    }

// 4) DASHBOARD
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

    // 🔹 Common user data (accessible sa view)
    $data = [
        'user' => [
            'id'    => $userID,
            'name'  => $session->get('name'),
            'email' => $session->get('email'),
            'role'  => $role
        ],
        'flash' => $session->getFlashdata('success'),
        // optional defaults para maiwasan undefined variable notice
        'totalUsers'   => null,
        'totalCourses' => null,
        'myCourses'    => [],
        'enrolled'     => [],
        'available'    => []
    ];

    // ===== ROLE-BASED LOGIC (lahat pupunta sa iisang view) =====
    if ($role === 'admin') {
        // Admin: show summary
        $data['totalUsers']   = $userModel->countAll();
        $data['totalCourses'] = $courseModel->countAll();

    } elseif ($role === 'teacher') {
        // Teacher: show own courses
        $data['myCourses'] = $courseModel->where('teacher_id', $userID)->findAll();

    } elseif ($role === 'student') {
        // Student: show enrolled and available courses
        $enrolled = $enrollmentModel->getUserEnrollments($userID);

        // Get enrolled course IDs
        $enrolledIDs = [];
        foreach ($enrolled as $course) {
            $enrolledIDs[] = $course['id']; // joined course ID
        }

        // Get available courses (not enrolled)
        if (!empty($enrolledIDs)) {
            $available = $courseModel->whereNotIn('id', $enrolledIDs)->findAll();
        } else {
            $available = $courseModel->findAll();
        }

        $data['enrolled']  = $enrolled;
        $data['available'] = $available;
    }

    // ✅ Lahat ng role iisang view lang
    return view('auth/dashboard', $data);
}

public function enroll($course_id)
{
    $session = \Config\Services::session();

    // Security: redirect kung hindi naka-login o hindi student
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'student') {
        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['message' => 'Unauthorized']);
        }
        return redirect()->to(base_url('login'));
    }

    $userID = $session->get('userID');

    // Load Enrollment Model
    $enrollmentModel = new \App\Models\EnrollmentModel();

    // Check kung naka-enroll na
    if ($enrollmentModel->isAlreadyEnrolled($userID, $course_id)) {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['message' => 'You are already enrolled in this course.']);
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
        return $this->response->setJSON(['message' => 'You have successfully enrolled in the course!']);
    }

    $session->setFlashdata('success', 'You have successfully enrolled in the course!');
    return redirect()->to(base_url('auth/dashboard'));
}
    


}
