<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }
        
        .navbar {
            background: white;
            padding: 15px 30px;
            border-bottom: 2px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #1976d2;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        
        .nav-links a:hover {
            color: #1976d2;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
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
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">Student LMS</div>
        <div class="nav-links">
            <a href="<?= base_url('dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('users') ?>">Manage Users</a>
            <a href="<?= base_url('courses') ?>">Manage Courses</a>
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Manage Users</h1>
                    <p>View and manage all registered users in the system</p>
                </div>
                <button class="btn btn-edit" onclick="openAddModal()" style="margin-top: 0;">
                    <i class="fas fa-plus"></i> Add New User
                </button>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= esc($u['id']) ?></td>
                                <td><?= esc($u['name']) ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td>
                                    <span class="badge badge-<?= esc($u['role']) ?>">
                                        <?= strtoupper(esc($u['role'])) ?>
                                    </span>
                                </td>
                                <td><?= !empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : 'N/A' ?></td>
                                <td>
                                    <?php if ($u['role'] !== 'admin'): ?>
                                        <div style="display: flex; gap: 8px;">
                                            <button class="btn btn-edit" onclick="openEditModal(<?= $u['id'] ?>, '<?= esc($u['name']) ?>', '<?= esc($u['role']) ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-delete" onclick="confirmDelete(<?= $u['id'] ?>, '<?= esc($u['name']) ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <button class="btn btn-disabled" disabled>
                                            <i class="fas fa-lock"></i> Protected
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #666;">
                                No users found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

    <script>
        function openAddModal() {
            document.getElementById('addUserForm').reset();
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(userId, userName, currentRole) {
            document.getElementById('editUserId').value = userId;
            document.getElementById('editUserName').value = userName;
            document.getElementById('editUserRole').value = currentRole;
            document.getElementById('editUserPassword').value = ''; // Clear password field
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmDelete(userId, userName) {
            if (confirm('Are you sure you want to delete user "' + userName + '"? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('users/delete') ?>';
                
                // Add CSRF token - get from existing form
                const csrfToken = document.querySelector('input[name="<?= csrf_token() ?>"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = csrfToken.name;
                    csrfInput.value = csrfToken.value;
                    form.appendChild(csrfInput);
                }
                
                // Add user_id
                const userIdInput = document.createElement('input');
                userIdInput.type = 'hidden';
                userIdInput.name = 'user_id';
                userIdInput.value = userId;
                form.appendChild(userIdInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const addModal = document.getElementById('addModal');
            if (event.target == editModal) {
                closeEditModal();
            }
            if (event.target == addModal) {
                closeAddModal();
            }
        }
    </script>
</body>
</html>

