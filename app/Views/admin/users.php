<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB',
                    },
                }
            }
        }
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
            width: 100%;
            position: fixed;
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }
        
        #sidebar-container {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
            width: 256px !important;
            z-index: 1000 !important;
            will-change: auto;
            transform: translateZ(0);
        }
        
        .sidebar-item {
            transition: background-color 0.2s ease;
        }
        
        .sidebar-item:hover {
            background-color: rgba(79, 70, 229, 0.1);
        }
        
        .lms-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .container {
            max-width: 100%;
            margin: 0;
            padding: 20px;
        }
        
        .page-header {
            background: white;
            padding: 25px;
            border: 3px solid #333;
            border-radius: 3px;
            margin-bottom: 25px;
        }
        
        .page-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 8px;
        }
        
        .page-header p {
            color: #666;
            font-size: 14px;
        }
        
        .users-table {
            background: white;
            border: 2px solid #999;
            border-radius: 3px;
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #1976d2;
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }
        
        td {
            padding: 15px;
            border-top: 2px solid #ddd;
            font-size: 14px;
        }
        
        tr:hover {
            background: #f5f5f5;
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
            border: 2px solid;
        }
        
        .badge-admin {
            background: #ffebee;
            color: #c62828;
            border-color: #c62828;
        }
        
        .badge-teacher {
            background: #e3f2fd;
            color: #1976d2;
            border-color: #1976d2;
        }
        
        .badge-student {
            background: #e8f5e9;
            color: #2e7d32;
            border-color: #2e7d32;
        }
        
        .btn {
            padding: 8px 15px;
            border: 2px solid;
            border-radius: 3px;
            font-weight: bold;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background: #1976d2;
            color: white;
            border-color: #1565c0;
        }
        
        .btn-edit:hover {
            background: #1565c0;
        }
        
        .btn-delete {
            background: #d32f2f;
            color: white;
            border-color: #c62828;
        }
        
        .btn-delete:hover {
            background: #c62828;
        }
        
        .btn-disabled {
            background: #ccc;
            color: #666;
            border-color: #999;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 12px;
            border-radius: 3px;
            margin-bottom: 20px;
            border: 2px solid;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 30px;
            border: 3px solid #333;
            border-radius: 3px;
            max-width: 500px;
            width: 90%;
        }
        
        .modal-header {
            margin-bottom: 20px;
        }
        
        .modal-header h2 {
            font-size: 22px;
            color: #333;
        }
        
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .form-group select:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }
        
        .btn-cancel {
            background: #ccc;
            color: #333;
            border-color: #999;
        }
        
        .btn-cancel:hover {
            background: #bbb;
        }
        
        .btn-save {
            background: #1976d2;
            color: white;
            border-color: #1565c0;
        }
        
        .btn-save:hover {
            background: #1565c0;
        }
        
        .close {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #666;
        }
        
        .close:hover {
            color: #333;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar Layout -->
    <div class="flex h-screen overflow-hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%;">
        <!-- Sidebar -->
        <div id="sidebar-container" class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-full h-full bg-white border-r border-gray-200" style="overflow-y: auto;">
                <div class="flex items-center justify-center h-16 px-4 lms-header shadow-lg">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl mr-3 text-white"></i>
                        <div>
                            <h1 class="text-xl font-bold text-white">LearnHub</h1>
                            <p class="text-xs text-white opacity-90">LMS Platform</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <nav class="flex-1 space-y-1">
                        <?php 
                        $currentPage = uri_string();
                        $isDashboard = ($currentPage == 'dashboard' || $currentPage == 'auth/dashboard');
                        ?>
                        
                        <!-- Dashboard -->
                        <a href="<?= base_url('dashboard') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $isDashboard ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                            <i class="w-5 h-5 mr-3 <?= $isDashboard ? '' : 'text-gray-500' ?> fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        
                        <?php if(session('role') == 'admin'): ?>
                            <!-- Admin Navigation -->
                            <div class="mt-4 mb-2">
                                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                            </div>
                            
                            <a href="<?= base_url('users') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'users' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'users' ? '' : 'text-gray-500' ?> fas fa-users"></i>
                                Manage Users
                            </a>
                            
                            <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'courses' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'courses' ? '' : 'text-gray-500' ?> fas fa-book"></i>
                                Manage Courses
                            </a>
                            
                            <div class="mt-4 mb-2">
                                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Configuration</p>
                            </div>
                            
                            <a href="<?= base_url('school-setup') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'school-setup' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'school-setup' ? '' : 'text-gray-500' ?> fas fa-cog"></i>
                                School Setup
                            </a>
                        <?php endif; ?>
                        
                        <hr class="my-4 border-gray-200">
                        
                        <div class="mb-2">
                            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Account</p>
                        </div>
                        
                        <a href="<?= base_url('profile') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'profile' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                            <i class="w-5 h-5 mr-3 <?= $currentPage == 'profile' ? '' : 'text-gray-500' ?> fas fa-user"></i>
                            Profile
                        </a>
                        
                        <a href="<?= base_url('settings') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'settings' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                            <i class="w-5 h-5 mr-3 <?= $currentPage == 'settings' ? '' : 'text-gray-500' ?> fas fa-cog"></i>
                            Settings
                        </a>
                        
                        <a href="<?= base_url('logout') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                            <i class="w-5 h-5 mr-3 text-red-500 fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </nav>
                </div>
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode(session('name') ?? 'Admin') ?>" alt="<?= esc(session('name') ?? 'Admin') ?>">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700"><?= esc(session('name') ?? 'Admin') ?></p>
                            <p class="text-xs text-gray-500"><?= ucfirst(esc(session('role') ?? 'admin')) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden" style="margin-left: 256px; width: calc(100% - 256px);">
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container" style="max-width: 100%; margin: 0; padding: 20px;">
                    <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Manage Users</h1>
                    <p>View and manage all registered users in the system</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-edit" onclick="toggleDeletedAccounts()" style="background: #ffc107; border-color: #ff9800; color: #856404;">
                        <i class="fas fa-trash-restore"></i> Recovery Account
                        <?php if (!empty($deletedUsers)): ?>
                            <span style="background: #d32f2f; color: white; padding: 2px 6px; border-radius: 10px; font-size: 11px; margin-left: 5px;">
                                <?= count($deletedUsers) ?>
                            </span>
                        <?php endif; ?>
                    </button>
                    <button class="btn btn-edit" onclick="openAddModal()" style="margin-top: 0;">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="page-header" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #333; font-size: 14px;">Search Users</label>
            <div style="display: flex; gap: 10px;">
                <div style="position: relative; flex: 1;">
                    <input type="text" 
                           id="searchUsersInput" 
                           placeholder="Type at least 2 letters to search by name, email, or role..." 
                           style="width: 100%; padding: 12px 12px 12px 40px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"
                           autocomplete="off"
                           oninput="if(typeof window.filterUsers === 'function') { window.filterUsers(); }"
                           onkeyup="if(typeof window.filterUsers === 'function') { window.filterUsers(); }"
                           onkeypress="if(event.key === 'Enter') { if(typeof window.filterUsers === 'function') { window.filterUsers(); } }">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #666;"></i>
                </div>
                <button type="button" 
                        onclick="if(typeof window.filterUsers === 'function') { window.filterUsers(); }"
                        style="padding: 12px 24px; background: #1976d2; color: white; border: 2px solid #1565c0; border-radius: 3px; font-weight: bold; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
            </div>
            <!-- No results message -->
            <div id="noUsersFound" class="hidden" style="margin-top: 15px; padding: 15px; border-radius: 3px; border-left: 4px solid #ff9800; background: #fff3cd; border: 2px solid #ffc107;">
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle" style="color: #ff9800; font-size: 20px; margin-right: 12px;"></i>
                    <div style="flex: 1;">
                        <p style="font-size: 14px; font-weight: 600; color: #856404; margin: 0;">No users found matching your search.</p>
                        <p style="font-size: 12px; color: #856404; margin: 5px 0 0 0;">Try adjusting your search terms.</p>
                    </div>
                    <button type="button" onclick="document.getElementById('searchUsersInput').value=''; filterUsers();" style="margin-left: auto; color: #856404; padding: 5px 10px; border: none; background: transparent; cursor: pointer; font-size: 18px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr class="user-row" 
                                data-user-name="<?= strtolower(esc($u['name'])) ?>"
                                data-user-email="<?= strtolower(esc($u['email'])) ?>"
                                data-user-role="<?= strtolower(esc($u['role'])) ?>"
                                data-user-id="<?= esc($u['id']) ?>">
                                <td><?= esc($u['id']) ?></td>
                                <td><?= esc($u['name']) ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= esc($u['role']) ?>">
                                        <?= strtoupper(esc($u['role'])) ?>
                                    </span>
                                </td>
                                <td><?= !empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : 'N/A' ?></td>
                                <td><?= !empty($u['updated_at']) ? date('M j, Y g:i A', strtotime($u['updated_at'])) : 'N/A' ?></td>
                                <td>
                                    <?php 
                                    $currentUserId = session('userID');
                                    $isCurrentUser = ($u['id'] == $currentUserId);
                                    $isAdminOffline = ($u['role'] === 'admin' && !$isCurrentUser && !($u['is_online'] ?? false));
                                    ?>
                                    
                                    <?php if ($u['role'] !== 'admin' || $isAdminOffline): ?>
                                        <div style="display: flex; gap: 8px;">
                                            <button class="btn btn-edit" data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>" data-user-role="<?= htmlspecialchars($u['role'], ENT_QUOTES, 'UTF-8') ?>" data-is-admin-offline="<?= $isAdminOffline ? 'true' : 'false' ?>" onclick="openEditModalFromButton(this)">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <?php if ($u['role'] !== 'admin' || $isAdminOffline): ?>
                                                <button class="btn btn-delete" data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?>" onclick="confirmDeleteFromButton(this)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <button class="btn btn-disabled" disabled>
                                            <i class="fas fa-lock"></i> <?= $isCurrentUser ? 'Your Account' : 'Online' ?>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #666;">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Deleted Accounts Section -->
        <div id="deletedAccountsSection" style="margin-top: 30px; display: none;">
            <?php if (!empty($deletedUsers)): ?>
                <div class="page-header" style="background: #fff3cd; border-color: #ffc107;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h1 style="color: #856404;">
                                <i class="fas fa-trash-restore"></i> Deleted Accounts
                            </h1>
                            <p style="color: #856404;">Recover deleted accounts here</p>
                        </div>
                        <button class="btn btn-cancel" onclick="toggleDeletedAccounts()" style="background: #6c757d; border-color: #5a6268;">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                </div>

                <div class="users-table" style="border-color: #ffc107;">
                    <table>
                        <thead style="background: #ffc107; color: #856404;">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Deleted On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deletedUsers as $du): ?>
                                <tr class="deleted-user-row" 
                                    style="background: #fff9e6;"
                                    data-user-name="<?= strtolower(esc($du['name'])) ?>"
                                    data-user-email="<?= strtolower(esc($du['email'])) ?>"
                                    data-user-role="<?= strtolower(esc($du['role'])) ?>"
                                    data-user-id="<?= esc($du['id']) ?>">
                                    <td><?= esc($du['id']) ?></td>
                                    <td><?= esc($du['name']) ?></td>
                                    <td><?= esc($du['email']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= esc($du['role']) ?>">
                                            <?= strtoupper(esc($du['role'])) ?>
                                        </span>
                                    </td>
                                    <td><?= !empty($du['deleted_at']) ? date('M j, Y g:i A', strtotime($du['deleted_at'])) : 'N/A' ?></td>
                                    <td>
                                        <button class="btn btn-edit" data-user-id="<?= $du['id'] ?>" data-user-name="<?= htmlspecialchars($du['name'], ENT_QUOTES, 'UTF-8') ?>" onclick="confirmRecoverFromButton(this)" style="background: #28a745; border-color: #1e7e34;">
                                            <i class="fas fa-undo"></i> Recover Account
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="users-table" style="border-color: #ffc107; padding: 40px; text-align: center;">
                    <p style="color: #856404; font-size: 16px;">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745; margin-bottom: 15px; display: block;"></i>
                        No deleted accounts to recover.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recover Account Confirmation Modal -->
    <div id="recoverModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeRecoverModal()">&times;</span>
                <h2>Recover Account</h2>
            </div>
            <div style="padding: 20px 0;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <i class="fas fa-undo" style="font-size: 48px; color: #28a745; margin-bottom: 15px;"></i>
                    <p style="font-size: 16px; color: #333; margin-bottom: 10px;">
                        <strong>Are you sure you want to recover this account?</strong>
                    </p>
                    <p style="font-size: 14px; color: #666; margin-bottom: 5px;">
                        User: <strong id="recoverUserName"></strong>
                    </p>
                    <p style="font-size: 13px; color: #28a745; font-weight: bold;">
                        ✅ This will restore the account and allow the user to login again.
                    </p>
                </div>
                <form id="recoverUserForm" method="POST" action="<?= base_url('users/recover') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" id="recoverUserId" name="user_id">
                    <div class="modal-actions">
                        <button type="button" class="btn btn-cancel" onclick="closeRecoverModal()">Cancel</button>
                        <button type="submit" class="btn btn-save" style="background: #28a745; border-color: #1e7e34;">
                            <i class="fas fa-undo"></i> Yes, Recover Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit User</h2>
            </div>
            <form id="editUserForm" method="POST" action="<?= base_url('users/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" id="editUserId" name="user_id">
                
                <div class="form-group">
                    <label for="editUserName">User Name</label>
                    <input type="text" id="editUserName" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="editUserRole">Role</label>
                    <select id="editUserRole" name="role" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="editUserPassword">New Password (optional)</label>
                    <input type="password" id="editUserPassword" name="password" placeholder="Leave blank to keep current password" style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px;">
                    <p style="margin-top: 5px; font-size: 12px; color: #666;">Leave blank if you don't want to change the password</p>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeAddModal()">&times;</span>
                <h2>Add New User</h2>
            </div>
            <form id="addUserForm" method="POST" action="<?= base_url('users/create') ?>">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="addUserName">Full Name</label>
                    <input type="text" id="addUserName" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="addUserEmail">Email Address</label>
                    <input type="email" id="addUserEmail" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="addUserRole">Role</label>
                    <select id="addUserRole" name="role" required>
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; border-radius: 3px; background: #d4edda; color: #155724; border: 2px solid #c3e6cb;">
                    <span style="font-weight: bold;">🔑 Auto-generated Password:</span> The password will be automatically set to <strong>akoaraka123</strong>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeDeleteModal()">&times;</span>
                <h2>Confirm Delete</h2>
            </div>
            <div style="padding: 20px 0;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #d32f2f; margin-bottom: 15px;"></i>
                    <p style="font-size: 16px; color: #333; margin-bottom: 10px;">
                        <strong>Are you sure you want to delete this account?</strong>
                    </p>
                    <p style="font-size: 14px; color: #666; margin-bottom: 5px;">
                        User: <strong id="deleteUserName"></strong>
                    </p>
                    <p style="font-size: 13px; color: #666; font-weight: bold;">
                        ℹ️ Account can be recovered from the Deleted Accounts section below.
                    </p>
                </div>
                <form id="deleteUserForm" method="POST" action="<?= base_url('users/delete') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" id="deleteUserId" name="user_id">
                    <div class="modal-actions">
                        <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="btn btn-delete">
                            <i class="fas fa-trash"></i> Yes, Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addUserForm').reset();
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(userId, userName, currentRole, isAdminOffline = false) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserName').value = userName;
            document.getElementById('editUserPassword').value = ''; // Clear password field
            
            // Set the role value
            document.getElementById('editUserRole').value = currentRole;
            document.getElementById('editModal').style.display = 'block';
        }

        function openEditModalFromButton(button) {
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            const currentRole = button.getAttribute('data-user-role');
            const isAdminOffline = button.getAttribute('data-is-admin-offline') === 'true';
            openEditModal(userId, userName, currentRole, isAdminOffline);
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(userId, userName) {
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteModal').style.display = 'block';
        }

        function confirmDeleteFromButton(button) {
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            confirmDelete(userId, userName);
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        function confirmRecover(userId, userName) {
            document.getElementById('recoverUserId').value = userId;
            document.getElementById('recoverUserName').textContent = userName;
            document.getElementById('recoverModal').style.display = 'block';
        }

        function confirmRecoverFromButton(button) {
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            confirmRecover(userId, userName);
        }

        function closeRecoverModal() {
            document.getElementById('recoverModal').style.display = 'none';
        }

        function toggleDeletedAccounts() {
            const section = document.getElementById('deletedAccountsSection');
            if (section) {
                if (section.style.display === 'none') {
                    section.style.display = 'block';
                    // Scroll to the section
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    section.style.display = 'none';
                }
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const addModal = document.getElementById('addModal');
            const deleteModal = document.getElementById('deleteModal');
            const recoverModal = document.getElementById('recoverModal');
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == deleteModal) {
                closeDeleteModal();
            }
            if (event.target == recoverModal) {
                closeRecoverModal();
            }
        }

        // Filter users - requires at least 2 letters
        window.filterUsers = function() {
            try {
                const searchInputEl = document.getElementById('searchUsersInput');
                if (!searchInputEl) {
                    return;
                }
                
                const searchTerm = searchInputEl.value ? searchInputEl.value.toLowerCase().trim() : '';
                const userRows = document.querySelectorAll('.user-row');
                const noResultsMsg = document.getElementById('noUsersFound');
                
                // If search term is less than 2 letters, show all items
                if (searchTerm.length < 2) {
                    userRows.forEach(row => {
                        row.style.display = '';
                    });
                    
                    // Hide no results message
                    if (noResultsMsg) {
                        noResultsMsg.classList.add('hidden');
                        noResultsMsg.style.display = 'none';
                    }
                    return;
                }
                
                // Search with at least 2 letters
                let visibleCount = 0;
                
                userRows.forEach(row => {
                    const userName = row.getAttribute('data-user-name') || '';
                    const userEmail = row.getAttribute('data-user-email') || '';
                    const userRole = row.getAttribute('data-user-role') || '';
                    
                    const matches = userName.includes(searchTerm) || 
                                  userEmail.includes(searchTerm) || 
                                  userRole.includes(searchTerm);
                    
                    if (matches) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (noResultsMsg) {
                    if (visibleCount === 0 && searchTerm.length >= 2) {
                        noResultsMsg.classList.remove('hidden');
                        noResultsMsg.style.display = 'block';
                    } else {
                        noResultsMsg.classList.add('hidden');
                        noResultsMsg.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error in filterUsers:', error);
            }
        };
    </script>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

