<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $session;
    protected $validation;
    protected $db;

    public function __construct()
    {
        // ✅ Reusable services (hindi na uulit-ulitin sa bawat method)
        $this->session    = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db         = \Config\Database::connect();
    }

    // 1) REGISTER
    public function register()
    {
        if ($this->session->get('isLoggedIn')) {
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
                $this->db->table('users')->insert([
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role'       => 'user', // default role
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $this->session->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to(base_url('login'));
            }

            $this->session->setFlashdata('errors', $this->validation->getErrors());
            return redirect()->back()->withInput();
        }

        return view('auth/register');
    }

// LOGIN FUNCTION
public function login()
{
    if ($this->session->get('isLoggedIn')) {
        return redirect()->to(base_url('dashboard'));
    }

    if ($this->request->getMethod() === 'POST') {
        // ✅ Validation rules
        $rules = [
            'login'    => 'required|min_length[3]', // pwedeng email o username
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $this->session->setFlashdata('error', 'Please enter both login and password.');
            return redirect()->back()->withInput();
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // ✅ Check kung email o username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Hanapin base sa email
            $user = $this->db->table('users')->where('email', $login)->get()->getRowArray();
        } else {
            // Hanapin base sa username
            $user = $this->db->table('users')->where('name', $login)->get()->getRowArray();
        }

        // ✅ User not found
        if (!$user) {
            $this->session->setFlashdata('error', 'Account not found.');
            return redirect()->back()->withInput();
        }

        // ✅ Check password
        if (!password_verify($password, $user['password'])) {
            $this->session->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // ✅ Success login - save session
        $this->session->set([
            'userID'     => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        $this->session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');

        // ✅ Role-based redirection
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($user['role'] === 'teacher') {
            return redirect()->to('/teacher/dashboard');
        } else {
            return redirect()->to('/student/dashboard');
        }
    }

    // Default view kung GET request
    return view('auth/login');

}


    // 3) LOGOUT
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }

    // 4) DASHBOARD
    public function dashboard()
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        return view('auth/dashboard', [
            'user'  => [
                'id'    => $this->session->get('userID'),
                'name'  => $this->session->get('name'),
                'email' => $this->session->get('email'),
                'role'  => $this->session->get('role')
            ],
            'flash' => $this->session->getFlashdata('success')
        ]);
    }
}
