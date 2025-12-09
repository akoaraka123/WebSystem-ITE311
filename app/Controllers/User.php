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

        // Get active users (not deleted)
        $users = $this->userModel->findAll();
        $currentUserId = $session->get('userID');
        
        // Add online status to each user
        foreach ($users as &$user) {
            $user['is_online'] = $this->isUserOnline($user['id'], $currentUserId);
        }

        // Get deleted users for recovery
        $deletedUsers = $this->userModel->onlyDeleted()->findAll();

        $data = [
            'title' => 'Manage Users - LMS',
            'users' => $users,
            'deletedUsers' => $deletedUsers,
            'user' => $session->get()
        ];

        return view('admin/users', $data);
    }

    /**
     * Check if a user is currently online (has active session)
     */
    private function isUserOnline($userId, $currentUserId)
    {
        // If checking current user, they're always online
        if ($userId == $currentUserId) {
            return true;
        }

        // Check session files for active sessions
        $sessionPath = WRITEPATH . 'session/';
        if (!is_dir($sessionPath)) {
            return false;
        }

        $sessionFiles = glob($sessionPath . 'ci_session*');
        $sessionExpiration = 7200; // 2 hours from config

        foreach ($sessionFiles as $file) {
            // Check if file is readable and not expired
            if (!is_readable($file)) {
                continue; // Skip if file is not readable
            }

            try {
                $fileTime = @filemtime($file);
                if ($fileTime === false || $fileTime + $sessionExpiration < time()) {
                    continue; // Session expired or can't read file time
                }

                $sessionData = @file_get_contents($file);
                if ($sessionData === false) {
                    continue; // Skip if can't read file
                }

                // Check if this session belongs to the user we're checking
                if (preg_match('/userID";s:\d+:"' . $userId . '"/', $sessionData) || 
                    preg_match('/"userID";i:' . $userId . '/', $sessionData)) {
                    // Also check if they're logged in
                    if (strpos($sessionData, 'isLoggedIn";b:1') !== false || 
                        strpos($sessionData, '"isLoggedIn";b:1') !== false) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                // Skip this file if there's an error reading it
                continue;
            }
        }

        return false;
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
            'role'  => 'required|in_list[student,teacher,admin]'
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

        // Validate role - allow admin if editing offline admin
        $currentUserId = $session->get('userID');
        $isEditingOfflineAdmin = false;
        
        // Get the user to check if they exist
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('users'));
        }

        // Check if editing an admin user
        if ($user['role'] === 'admin') {
            // Allow editing if:
            // 1. Not editing yourself (current user)
            // 2. The admin being edited is offline
            if ($userId == $currentUserId) {
                $session->setFlashdata('error', 'You cannot edit your own account.');
                return redirect()->to(base_url('users'));
            }
            
            // Check if the admin is online
            if ($this->isUserOnline($userId, $currentUserId)) {
                $session->setFlashdata('error', 'Cannot edit an admin who is currently online.');
                return redirect()->to(base_url('users'));
            }
            
            // Admin is offline, allow editing
            $isEditingOfflineAdmin = true;
        }

        // Validate role - allow admin role for all users
        if (!in_array($newRole, ['student', 'teacher', 'admin'])) {
            $session->setFlashdata('error', 'Invalid role selected.');
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

        $currentUserId = $session->get('userID');
        
        // Protect admin users from being deleted, except offline admins
        if ($user['role'] === 'admin') {
            // Cannot delete yourself
            if ($userId == $currentUserId) {
                $session->setFlashdata('error', 'You cannot delete your own account.');
                return redirect()->to(base_url('users'));
            }
            
            // Check if the admin is online
            if ($this->isUserOnline($userId, $currentUserId)) {
                $session->setFlashdata('error', 'Cannot delete an admin who is currently online.');
                return redirect()->to(base_url('users'));
            }
            
            // Admin is offline, allow deletion
        }

        // Delete the user (soft delete)
        $this->userModel->delete($userId);
        
        $session->setFlashdata('success', 'User deleted successfully! Account can be recovered from the deleted accounts section.');
        return redirect()->to(base_url('users'));
    }

    public function recoverAccount()
    {
        $session = session();
        
        // Check if user is logged in and is admin
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            $session->setFlashdata('error', 'Unauthorized access.');
            return redirect()->to(base_url('dashboard'));
        }

        $userId = (int) $this->request->getPost('user_id');

        if (!$userId) {
            $session->setFlashdata('error', 'Invalid user ID.');
            return redirect()->to(base_url('users'));
        }

        // Get the deleted user
        $user = $this->userModel->withDeleted()->find($userId);
        
        if (!$user) {
            $session->setFlashdata('error', 'User not found.');
            return redirect()->to(base_url('users'));
        }

        // Check if user is actually deleted
        if (empty($user['deleted_at'])) {
            $session->setFlashdata('error', 'This account is not deleted.');
            return redirect()->to(base_url('users'));
        }

        // Restore the user (remove deleted_at)
        $this->userModel->withDeleted()->update($userId, [
            'deleted_at' => null
        ]);
        
        $session->setFlashdata('success', 'Account recovered successfully! User: ' . $user['name']);
        return redirect()->to(base_url('users'));
    }
}
