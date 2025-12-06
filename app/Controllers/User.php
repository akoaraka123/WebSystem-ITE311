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

    public function create()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('dashboard'));
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'role'  => 'required|in_list[student,teacher]'
        ];

        if ($this->validate($rules)) {
            // Auto-generate password
            $autoPassword = 'akoaraka123';
            
            // Use UserModel to handle password hashing automatically
            $this->userModel->insert([
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => $autoPassword, // Model will hash this automatically
                'role'     => $this->request->getPost('role')
            ]);

            $session->setFlashdata('success', 'User created successfully! Password: ' . $autoPassword);
            return redirect()->to(base_url('users'));
        } else {
            $session->setFlashdata('error', 'Please correct the errors below.');
            return redirect()->to(base_url('users'))->withInput();
        }
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
            // Don't hash here - UserModel's beforeUpdate hook will handle it
            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
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

    public function update()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('dashboard'));
        }

        $userId = (int) $this->request->getPost('user_id');
        $newRole = $this->request->getPost('role');
        $newName = $this->request->getPost('name');

        // Validate name
        if (empty($newName) || strlen($newName) < 3 || strlen($newName) > 100) {
            $session->setFlashdata('error', 'Name must be between 3 and 100 characters.');
            return redirect()->to(base_url('users'));
        }

        // Validate role
        if (!in_array($newRole, ['student', 'teacher'])) {
            $session->setFlashdata('error', 'Invalid role selected.');
            return redirect()->to(base_url('users'));
        }

        // Get the user to check if they exist and are not admin
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('users'));
        }

        // Protect admin users from being edited
        if ($user['role'] === 'admin') {
            $session->setFlashdata('error', 'Admin users cannot be edited.');
            return redirect()->to(base_url('users'));
        }

        // Prepare update data
        $updateData = [
            'name' => $newName,
            'role' => $newRole
        ];

        // If password is provided, validate and update it
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) < 6) {
                $session->setFlashdata('error', 'Password must be at least 6 characters long.');
                return redirect()->to(base_url('users'));
            }
            // Don't hash here - UserModel's beforeUpdate hook will handle it
            $updateData['password'] = $newPassword;
        }

        // Update the user's information
        $this->userModel->update($userId, $updateData);
        
        $session->setFlashdata('success', 'User information updated successfully!');
        return redirect()->to(base_url('users'));
    }

    public function delete()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('dashboard'));
        }

        $userId = (int) $this->request->getPost('user_id');

        // Get the user to check if they exist and are not admin
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('users'));
        }

        // Protect admin users from being deleted
        if ($user['role'] === 'admin') {
            $session->setFlashdata('error', 'Admin users cannot be deleted.');
            return redirect()->to(base_url('users'));
        }

        // Delete the user
        $this->userModel->delete($userId);
        
        $session->setFlashdata('success', 'User deleted successfully!');
        return redirect()->to(base_url('users'));
    }
}
