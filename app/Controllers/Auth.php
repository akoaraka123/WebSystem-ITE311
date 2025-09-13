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

// 2) LOGIN
public function login()
{
    if ($this->session->get('isLoggedIn')) {
        return redirect()->to(base_url('dashboard'));
    }

    if ($this->request->getMethod() === 'POST') {
        // ✅ Validation rules
        $rules = [
            'login'    => 'required|min_length[3]',   // email or username
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            // Custom error message kapag empty
            $errors = $this->validation->getErrors();

            if (isset($errors['login'])) {
                $this->session->setFlashdata('error', 'Please enter your email or username.');
            } elseif (isset($errors['password'])) {
                $this->session->setFlashdata('error', 'Please enter your password.');
            } else {
                $this->session->setFlashdata('error', 'Invalid input. Please try again.');
            }

            return redirect()->back()->withInput();
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        // Check kung email ba or username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Kung email
            $user = $this->db->table('users')
                             ->where('email', $login)
                             ->get()
                             ->getRowArray();

            if (!$user) {
                $this->session->setFlashdata('error', 'Email not found or not registered.');
                return redirect()->back()->withInput();
            }
        } else {
            // Kung username
            $user = $this->db->table('users')
                             ->where('name', $login)
                             ->get()
                             ->getRowArray();

            if (!$user) {
                $this->session->setFlashdata('error', 'Username not found.');
                return redirect()->back()->withInput();
            }
        }

        // Check password
        if (!password_verify($password, $user['password'])) {
            $this->session->setFlashdata('error', 'Incorrect password.');
            return redirect()->back()->withInput();
        }

        // ✅ Success login
        $this->session->set([
            'userID'     => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true
        ]);

        $this->session->setFlashdata('success', 'Welcome back, ' . $user['name'] . '!');
        return redirect()->to(base_url('dashboard'));
    }

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
