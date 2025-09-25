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

// 4) DASHBOARD
public function dashboard()
{
    $session = \Config\Services::session();

    // Security: redirect kung hindi naka-login
    if (!$session->get('isLoggedIn')) {
        return redirect()->to(base_url('login'));
    }

    $role   = $session->get('role');
    $userID = $session->get('userID');

    // Instantiate models
    $userModel       = new \App\Models\UserModel();
    $courseModel     = new \App\Models\CourseModel();
    $enrollmentModel = new \App\Models\EnrollmentModel();

    $data = [
        'user'  => [
            'id'    => $userID,
            'name'  => $session->get('name'),
            'email' => $session->get('email'),
            'role'  => $role
        ],
        'flash' => $session->getFlashdata('success')
    ];

    // Role-specific data
    if ($role == 'admin') {
        $data['totalUsers']   = $userModel->countAll();
        $data['totalCourses'] = $courseModel->countAll();

    } elseif ($role == 'teacher') {
        $data['myCourses'] = $courseModel->where('teacher_id', $userID)->findAll();

    } elseif ($role == 'student') {
        $data['enrolledCourses'] = $enrollmentModel->where('student_id', $userID)->findAll();
    }

    return view('auth/dashboard', $data);
}

}
