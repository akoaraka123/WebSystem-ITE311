<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to(base_url('dashboard'));
        }

        $data = [
            'title' => 'Manage Users - LMS',
            'users' => $this->userModel->findAll(),
            'user' => $session->get()
        ];

        return view('admin/users', $data);
    }

    public function profile()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => 'My Profile - LMS',
            'user' => [
                'userID' => $session->get('userID'),
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'created_at' => $session->get('created_at'),
                'last_login' => $session->get('last_login')
            ]
        ];

        return view('auth/profile', $data);
    }

    public function updateProfile()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email'
        ];

        if ($this->validate($rules)) {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ];

            // If password is provided, update it too
            if ($this->request->getPost('password')) {
                $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            $this->userModel->update($session->get('userID'), $data);
            
            // Update session
            $session->set('name', $data['name']);
            $session->set('email', $data['email']);

            $session->setFlashdata('success', 'Profile updated successfully!');
            return redirect()->to(base_url('profile'));
        } else {
            $session->setFlashdata('error', 'Please correct the errors below.');
            return redirect()->to(base_url('profile'))->withInput();
        }
    }

    public function settings()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        $data = [
            'title' => 'Settings - LMS',
            'user' => [
                'userID' => $session->get('userID'),
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'role' => $session->get('role'),
                'created_at' => $session->get('created_at'),
                'last_login' => $session->get('last_login')
            ]
        ];

        return view('auth/settings', $data);
    }

    public function updateSettings()
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Handle settings update logic here
        $session->setFlashdata('success', 'Settings updated successfully!');
        return redirect()->to(base_url('settings'));
    }
}
