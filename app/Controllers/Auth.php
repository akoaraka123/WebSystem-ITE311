<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $session;
    protected $validation;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name'             => 'required|min_length[3]|max_length[100]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'password'         => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
                'role'             => 'required|in_list[student,instructor,admin]'
            ];

            if ($this->validate($rules)) {
                $hashedPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

                $userData = [
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'password'   => $hashedPassword,
                    'role'       => $this->request->getPost('role'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $builder = $this->db->table('users');
                if ($builder->insert($userData)) {
                    $this->session->setFlashdata('success', 'Registration successful! Please login.');
                    return redirect()->to(base_url('login'));
                } else {
                    $this->session->setFlashdata('error', 'Registration failed. Please try again.');
                }
            } else {
                $this->session->setFlashdata('errors', $this->validation->getErrors());
                return redirect()->back()->withInput();
            }
        }

        return view('auth/register');
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            $login = $this->request->getPost('login');
            $password = $this->request->getPost('password');

            if (empty($login) || empty($password)) {
                $this->session->setFlashdata('error', 'Please enter both login and password.');
                return view('auth/login');
            }

            $builder = $this->db->table('users');
            $user = $builder->where('email', $login)
                           ->orWhere('name', $login)
                           ->get()
                           ->getRowArray();

            if ($user && password_verify($password, $user['password'])) {
                $sessionData = [
                    'userID'     => $user['id'],
                    'name'       => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ];
                $this->session->set($sessionData);

                // ✅ Redirect to role-specific dashboard
                if ($user['role'] === 'admin') {
                    return redirect()->to(base_url('admin/dashboard'));
                } elseif ($user['role'] === 'instructor') {
                    return redirect()->to(base_url('instructor/dashboard'));
                } else {
                    return redirect()->to(base_url('student/dashboard'));
                }
            } else {
                $this->session->setFlashdata('error', 'Invalid login credentials.');
            }
        }

        return view('auth/login');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }

    // ✅ Generic dashboard route (just redirects to correct role dashboard)
    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $role = $this->session->get('role');

        if ($role === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        } elseif ($role === 'instructor') {
            return redirect()->to(base_url('instructor/dashboard'));
        } else {
            return redirect()->to(base_url('student/dashboard'));
        }
    }

    private function isLoggedIn(): bool
    {
        return $this->session->get('isLoggedIn') === true;
    }

    // ✅ Each role has its own dashboard view
    public function adminDashboard()
    {
        return view('auth/admin_dashboard', ['user' => $this->getUser()]);
    }

    public function instructorDashboard()
    {
        return view('auth/instructor_dashboard', ['user' => $this->getUser()]);
    }

    public function studentDashboard()
    {
        return view('auth/student_dashboard', ['user' => $this->getUser()]);
    }

    private function getUser(): array
    {
        return [
            'userID' => $this->session->get('userID'),
            'name'   => $this->session->get('name'),
            'email'  => $this->session->get('email'),
            'role'   => $this->session->get('role')
        ];
    }
}
