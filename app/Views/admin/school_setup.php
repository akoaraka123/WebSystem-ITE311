<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <title>School Setup - LMS</title>
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
            overflow-y: auto;
            overflow-x: hidden;
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background: #f0f0f0;
        }
        
        .sidebar {
            transition: all 0.3s;
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
        
        .section-card {
            background: white;
            border: 2px solid #999;
            border-radius: 3px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
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
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1976d2;
        }
        
        .btn {
            padding: 10px 20px;
            border: 2px solid;
            border-radius: 3px;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #1976d2;
            color: white;
            border-color: #1565c0;
        }
        
        .btn-primary:hover {
            background: #1565c0;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
            border-color: #1e7e34;
        }
        
        .btn-success:hover {
            background: #1e7e34;
        }
        
        .btn-danger {
            background: #d32f2f;
            color: white;
            border-color: #c62828;
        }
        
        .btn-danger:hover {
            background: #c62828;
        }
        
        .btn-secondary {
            background: #ccc;
            color: #333;
            border-color: #999;
        }
        
        .btn-secondary:hover {
            background: #bbb;
        }
        
        .alert {
            padding: 12px;
            border-radius: 3px;
            margin-bottom: 20px;
            border: 2px solid;
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
        
        .badge {
            padding: 4px 12px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 12px;
            border: 2px solid;
        }
        
        .badge-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-color: #2e7d32;
        }
        
        .badge-secondary {
            background: #e0e0e0;
            color: #424242;
            border-color: #757575;
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
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            margin-bottom: 20px;
        }
        
        .modal-header h2 {
            font-size: 22px;
            color: #333;
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
        
        .active-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
            margin-right: 8px;
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
            <h1>School Setup</h1>
            <p>Configure school year, semester, dates, and programs</p>
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

        <!-- Academic Year Management Section -->
        <div class="section-card" style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title" style="margin: 0;">
                    <i class="fas fa-calendar" style="margin-right: 10px;"></i>
                    Academic Year Management
                </h2>
                <button type="button" class="btn btn-success" onclick="openAddAcademicYearModal()">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>
                    Add Academic Year
                </button>
            </div>
            
            <?php if (!empty($academicYears)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Display Name</th>
                            <th>Year Start</th>
                            <th>Year End</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($academicYears as $acadYear): ?>
                            <tr>
                                <td><strong><?= esc($acadYear['display_name']) ?></strong></td>
                                <td><?= esc($acadYear['year_start']) ?></td>
                                <td><?= esc($acadYear['year_end']) ?></td>
                                <td>
                                    <span class="badge <?= $acadYear['is_active'] == 1 ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $acadYear['is_active'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; margin-right: 5px;"
                                            onclick="openEditAcademicYearModal(<?= $acadYear['id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"
                                            onclick="deleteAcademicYear(<?= $acadYear['id'] ?>, '<?= esc($acadYear['display_name']) ?>')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; padding: 20px; text-align: center;">No academic years found. Please add one.</p>
            <?php endif; ?>
        </div>

        <!-- Semester Management Section -->
        <div class="section-card" style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title" style="margin: 0;">
                    <i class="fas fa-calendar-alt" style="margin-right: 10px;"></i>
                    Semester Management
                </h2>
                <button type="button" class="btn btn-success" onclick="openAddSemesterModal()">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>
                    Add Semester
                </button>
            </div>
            
            <?php if (!empty($semesters)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Academic Year</th>
                            <th>Semester Number</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semesters as $semester): ?>
                            <tr>
                                <td><?= esc($semester['acad_year_name'] ?? 'N/A') ?></td>
                                <td><?= esc($semester['semester_number']) ?></td>
                                <td><strong><?= esc($semester['name']) ?></strong></td>
                                <td><?= $semester['start_date'] ? date('M d, Y', strtotime($semester['start_date'])) : 'N/A' ?></td>
                                <td><?= $semester['end_date'] ? date('M d, Y', strtotime($semester['end_date'])) : 'N/A' ?></td>
                                <td>
                                    <span class="badge <?= $semester['is_active'] == 1 ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $semester['is_active'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; margin-right: 5px;"
                                            onclick="openEditSemesterModal(<?= $semester['id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"
                                            onclick="deleteSemester(<?= $semester['id'] ?>, '<?= esc($semester['name']) ?>')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: #666; padding: 20px; text-align: center;">No semesters found. Please add one.</p>
            <?php endif; ?>
        </div>

        <!-- Programs Section -->
        <div class="section-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="section-title" style="margin: 0;">
                    <i class="fas fa-graduation-cap" style="margin-right: 10px;"></i>
                    Programs (BSIT, BSBA, etc.)
                </h2>
                <button type="button" class="btn btn-success" onclick="openAddProgramModal()">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>
                    Add Program
                </button>
            </div>
            
            <?php if (!empty($programs)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programs as $program): ?>
                            <tr>
                                <td><strong><?= esc($program['code']) ?></strong></td>
                                <td><?= esc($program['name']) ?></td>
                                <td><?= esc($program['description'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="badge <?= $program['is_active'] == 1 ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $program['is_active'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; margin-right: 5px;"
                                            onclick="openEditProgramModal(<?= $program['id'] ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;"
                                            onclick="deleteProgram(<?= $program['id'] ?>, '<?= esc($program['code']) ?>')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <i class="fas fa-graduation-cap" style="font-size: 48px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                    <p>No programs added yet. Click "Add Program" to get started.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Academic Year Modal -->
    <div id="academicYearModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeAcademicYearModal()">&times;</span>
                <h2 id="academicYearModalTitle">Add Academic Year</h2>
            </div>
            <form id="academicYearForm">
                <?= csrf_field() ?>
                <input type="hidden" id="acad_year_id" name="acad_year_id">
                
                <div class="form-group">
                    <label for="year_start">Year Start *</label>
                    <input type="number" id="year_start" name="year_start" required min="2000" max="2100"
                           placeholder="e.g., 2024">
                </div>
                
                <div class="form-group">
                    <label for="year_end">Year End *</label>
                    <input type="number" id="year_end" name="year_end" required min="2000" max="2100"
                           placeholder="e.g., 2025">
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" id="acad_year_is_active" name="is_active" value="1" checked
                               style="width: auto; margin-right: 8px;">
                        <span>Active</span>
                    </label>
                </div>
                
                <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeAcademicYearModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Academic Year</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Semester Modal -->
    <div id="semesterModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeSemesterModal()">&times;</span>
                <h2 id="semesterModalTitle">Add Semester</h2>
            </div>
            <form id="semesterForm">
                <?= csrf_field() ?>
                <input type="hidden" id="semester_id" name="semester_id">
                
                <div class="form-group">
                    <label for="semester_acad_year_id">Academic Year *</label>
                    <select id="semester_acad_year_id" name="acad_year_id" required>
                        <option value="">Select Academic Year</option>
                        <?php foreach($academicYears as $acadYear): ?>
                            <option value="<?= $acadYear['id'] ?>"><?= esc($acadYear['display_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="semester_number">Semester Number *</label>
                    <select id="semester_number" name="semester_number" required>
                        <option value="">Select</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                        <option value="3">Summer</option>
                    </select>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="semester_start_date">Start Date</label>
                        <input type="date" id="semester_start_date" name="start_date">
                    </div>
                    
                    <div class="form-group">
                        <label for="semester_end_date">End Date</label>
                        <input type="date" id="semester_end_date" name="end_date">
                    </div>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" id="semester_is_active" name="is_active" value="1" checked
                               style="width: auto; margin-right: 8px;">
                        <span>Active</span>
                    </label>
                </div>
                
                <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeSemesterModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Semester</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add/Edit Program Modal -->
    <div id="programModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeProgramModal()">&times;</span>
                <h2 id="programModalTitle">Add Program</h2>
            </div>
            <form id="programForm">
                <?= csrf_field() ?>
                <input type="hidden" id="program_id" name="program_id">
                
                <div class="form-group">
                    <label for="program_code">Program Code *</label>
                    <input type="text" id="program_code" name="code" required
                           placeholder="e.g., BSIT, BSBA, BSCS" style="text-transform: uppercase;">
                    <small style="color: #666;">Short code for the program (e.g., BSIT)</small>
                </div>
                
                <div class="form-group">
                    <label for="program_name">Program Name *</label>
                    <input type="text" id="program_name" name="name" required
                           placeholder="e.g., Bachelor of Science in Information Technology">
                </div>
                
                <div class="form-group">
                    <label for="program_description">Description</label>
                    <textarea id="program_description" name="description" rows="4"
                              placeholder="Optional description of the program"></textarea>
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" id="program_is_active" name="is_active" value="1" checked
                               style="width: auto; margin-right: 8px;">
                        <span>Active</span>
                    </label>
                </div>
                
                <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeProgramModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Program</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Make sure jQuery is loaded
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded!');
        }
        
        // Program Modal Functions (defined globally so they're available immediately)
        function openAddProgramModal() {
            try {
                const modal = document.getElementById('programModal');
                const title = document.getElementById('programModalTitle');
                const form = document.getElementById('programForm');
                const programId = document.getElementById('program_id');
                const isActive = document.getElementById('program_is_active');
                
                if (!modal) {
                    alert('Error: Program modal not found. Please refresh the page.');
                    return;
                }
                
                if (title) title.textContent = 'Add Program';
                if (form) form.reset();
                if (programId) programId.value = '';
                if (isActive) isActive.checked = true;
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error opening program modal:', error);
                alert('Error opening program modal. Please check console for details.');
            }
        }

        function openEditProgramModal(programId) {
            if (typeof $ === 'undefined') {
                alert('Please wait for the page to fully load.');
                return;
            }
            
            $.get('<?= base_url('school-setup/getProgram') ?>/' + programId, function(response) {
                if (response.success) {
                    const program = response.program;
                    const modal = document.getElementById('programModal');
                    const title = document.getElementById('programModalTitle');
                    
                    if (title) title.textContent = 'Edit Program';
                    if (document.getElementById('program_id')) document.getElementById('program_id').value = program.id;
                    if (document.getElementById('program_code')) document.getElementById('program_code').value = program.code;
                    if (document.getElementById('program_name')) document.getElementById('program_name').value = program.name;
                    if (document.getElementById('program_description')) document.getElementById('program_description').value = program.description || '';
                    if (document.getElementById('program_is_active')) document.getElementById('program_is_active').checked = program.is_active == 1;
                    if (modal) modal.style.display = 'block';
                } else {
                    alert('Error loading program: ' + response.message);
                }
            }).fail(function() {
                alert('Failed to load program details.');
            });
        }

        function closeProgramModal() {
            const modal = document.getElementById('programModal');
            if (modal) modal.style.display = 'none';
        }

        // Make all functions globally accessible immediately
        window.openAddProgramModal = openAddProgramModal;
        window.openEditProgramModal = openEditProgramModal;
        window.closeProgramModal = closeProgramModal;

        // Academic Year Modal Functions (defined globally so they're available immediately)
        function openAddAcademicYearModal() {
            try {
                const modal = document.getElementById('academicYearModal');
                const title = document.getElementById('academicYearModalTitle');
                const form = document.getElementById('academicYearForm');
                const acadYearId = document.getElementById('acad_year_id');
                const isActive = document.getElementById('acad_year_is_active');
                
                if (!modal) {
                    alert('Error: Academic Year modal not found. Please refresh the page.');
                    return;
                }
                
                if (title) title.textContent = 'Add Academic Year';
                if (form) form.reset();
                if (acadYearId) acadYearId.value = '';
                if (isActive) isActive.checked = true;
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error opening academic year modal:', error);
                alert('Error opening academic year modal. Please check console for details.');
            }
        }

        function openEditAcademicYearModal(acadYearId) {
            if (typeof $ === 'undefined') {
                alert('Please wait for the page to fully load.');
                return;
            }
            
            $.get('<?= base_url('school-setup/getAcademicYear') ?>/' + acadYearId, function(response) {
                if (response.success) {
                    const acadYear = response.academicYear;
                    const modal = document.getElementById('academicYearModal');
                    const title = document.getElementById('academicYearModalTitle');
                    
                    if (title) title.textContent = 'Edit Academic Year';
                    if (document.getElementById('acad_year_id')) document.getElementById('acad_year_id').value = acadYear.id;
                    if (document.getElementById('year_start')) document.getElementById('year_start').value = acadYear.year_start;
                    if (document.getElementById('year_end')) document.getElementById('year_end').value = acadYear.year_end;
                    if (document.getElementById('acad_year_is_active')) document.getElementById('acad_year_is_active').checked = acadYear.is_active == 1;
                    if (modal) modal.style.display = 'block';
                } else {
                    alert('Error loading academic year: ' + response.message);
                }
            }).fail(function() {
                alert('Failed to load academic year details.');
            });
        }

        function closeAcademicYearModal() {
            const modal = document.getElementById('academicYearModal');
            if (modal) modal.style.display = 'none';
        }

        // Make Academic Year functions globally accessible immediately
        window.openAddAcademicYearModal = openAddAcademicYearModal;
        window.openEditAcademicYearModal = openEditAcademicYearModal;
        window.closeAcademicYearModal = closeAcademicYearModal;

        // Semester Modal Functions (defined globally)
        function openAddSemesterModal() {
            try {
                const modal = document.getElementById('semesterModal');
                const title = document.getElementById('semesterModalTitle');
                const form = document.getElementById('semesterForm');
                const semesterId = document.getElementById('semester_id');
                const isActive = document.getElementById('semester_is_active');
                
                if (!modal) {
                    alert('Error: Semester modal not found. Please refresh the page.');
                    return;
                }
                
                if (title) title.textContent = 'Add Semester';
                if (form) form.reset();
                if (semesterId) semesterId.value = '';
                if (isActive) isActive.checked = true;
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error opening semester modal:', error);
                alert('Error opening semester modal. Please check console for details.');
            }
        }

        function openEditSemesterModal(semesterId) {
            if (typeof $ === 'undefined') {
                alert('Please wait for the page to fully load.');
                return;
            }
            
            $.get('<?= base_url('school-setup/getSemester') ?>/' + semesterId, function(response) {
                if (response.success) {
                    const semester = response.semester;
                    const modal = document.getElementById('semesterModal');
                    const title = document.getElementById('semesterModalTitle');
                    
                    if (title) title.textContent = 'Edit Semester';
                    if (document.getElementById('semester_id')) document.getElementById('semester_id').value = semester.id;
                    if (document.getElementById('semester_acad_year_id')) document.getElementById('semester_acad_year_id').value = semester.acad_year_id;
                    if (document.getElementById('semester_number')) document.getElementById('semester_number').value = semester.semester_number;
                    if (document.getElementById('semester_start_date')) document.getElementById('semester_start_date').value = semester.start_date || '';
                    if (document.getElementById('semester_end_date')) document.getElementById('semester_end_date').value = semester.end_date || '';
                    if (document.getElementById('semester_is_active')) document.getElementById('semester_is_active').checked = semester.is_active == 1;
                    if (modal) modal.style.display = 'block';
                } else {
                    alert('Error loading semester: ' + response.message);
                }
            }).fail(function() {
                alert('Failed to load semester details.');
            });
        }

        function closeSemesterModal() {
            const modal = document.getElementById('semesterModal');
            if (modal) modal.style.display = 'none';
        }

        // Make Semester functions globally accessible immediately
        window.openAddSemesterModal = openAddSemesterModal;
        window.openEditSemesterModal = openEditSemesterModal;
        window.closeSemesterModal = closeSemesterModal;

        // Modal functions are now available globally
            openAddProgramModal: typeof window.openAddProgramModal,
            openAddAcademicYearModal: typeof window.openAddAcademicYearModal,
            openAddSemesterModal: typeof window.openAddSemesterModal
        });

        // Wait for DOM to be ready
        $(document).ready(function() {
            // Get CSRF token from meta tag
            function getCSRFToken() {
                return $('meta[name="<?= csrf_token() ?>"]').attr('content');
            }
            
            function getCSRFTokenName() {
                return '<?= csrf_token() ?>';
            }
            

            // Program modal functions are already defined globally above
            // Save Program
            $('#programForm').on('submit', function(e) {
                e.preventDefault();
                
                // Serialize form data including CSRF token
                let formData = $('#programForm').serialize();
                
                // Update code to uppercase and is_active
                const code = $('#program_code').val().toUpperCase().trim();
                const isActive = $('#program_is_active').is(':checked') ? '1' : '0';
                
                // Replace code and is_active in serialized data
                formData = formData.replace(/code=[^&]*/, 'code=' + encodeURIComponent(code));
                formData = formData.replace(/is_active=[^&]*/, 'is_active=' + isActive);
                if (!formData.includes('is_active=')) {
                    formData += '&is_active=' + isActive;
                }

                $.ajax({
                url: '<?= base_url('school-setup/saveProgram') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Update CSRF token if provided
                    if (response.csrf_hash) {
                        const csrfTokenName = getCSRFTokenName();
                        $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                        $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                    }
                    
                    if (response.success) {
                        alert(response.message);
                        closeProgramModal();
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                    }
                    alert(errorMsg);
                }
            });
            }); // Close $('#programForm').on('submit')

            // Delete Program
            window.deleteProgram = function(programId, programCode) {
                if (!confirm('Are you sure you want to delete program "' + programCode + '"?\n\nThis action cannot be undone.')) {
                    return;
                }

                const formData = {};
                const csrfTokenName = getCSRFTokenName();
                const csrfToken = getCSRFToken() || $('input[name="' + csrfTokenName + '"]').val();
                formData[csrfTokenName] = csrfToken;

                $.ajax({
                    url: '<?= base_url('school-setup/deleteProgram') ?>/' + programId,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                        }
                        
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403) {
                            errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                        } else if (xhr.responseText) {
                            if (xhr.responseText.includes('not allowed') || xhr.responseText.includes('CSRF')) {
                                errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                            }
                        }
                        alert(errorMsg);
                    }
                });
            };

            // Delete Academic Year
            window.deleteAcademicYear = function(acadYearId, displayName) {
                if (!confirm('Are you sure you want to delete academic year "' + displayName + '"?\n\nThis action cannot be undone.')) {
                    return;
                }

                const formData = {};
                const csrfTokenName = getCSRFTokenName();
                const csrfToken = getCSRFToken() || $('input[name="' + csrfTokenName + '"]').val();
                formData[csrfTokenName] = csrfToken;

                $.ajax({
                    url: '<?= base_url('school-setup/deleteAcademicYear') ?>/' + acadYearId,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                        }
                        
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403) {
                            errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                        } else if (xhr.responseText) {
                            if (xhr.responseText.includes('not allowed') || xhr.responseText.includes('CSRF')) {
                                errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                            }
                        }
                        alert(errorMsg);
                    }
                });
            };

            // Delete Semester
            window.deleteSemester = function(semesterId, semesterName) {
                if (!confirm('Are you sure you want to delete semester "' + semesterName + '"?\n\nThis action cannot be undone.')) {
                    return;
                }

                const formData = {};
                const csrfTokenName = getCSRFTokenName();
                const csrfToken = getCSRFToken() || $('input[name="' + csrfTokenName + '"]').val();
                formData[csrfTokenName] = csrfToken;

                $.ajax({
                    url: '<?= base_url('school-setup/deleteSemester') ?>/' + semesterId,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                        }
                        
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403) {
                            errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                        } else if (xhr.responseText) {
                            if (xhr.responseText.includes('not allowed') || xhr.responseText.includes('CSRF')) {
                                errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                            }
                        }
                        alert(errorMsg);
                    }
                });
            };

            // Auto-generate display name from year start and year end
            function generateDisplayName() {
                const yearStart = $('#year_start').val();
                const yearEnd = $('#year_end').val();
                if (yearStart && yearEnd) {
                    // Display name will be auto-generated on backend, but we can show a preview
                    // The backend will handle the actual generation
                }
            }
            
            $('#year_start, #year_end').on('input change', function() {
                generateDisplayName();
            });

            // Save Academic Year
            $('#academicYearForm').on('submit', function(e) {
                e.preventDefault();
                
                let formData = $('#academicYearForm').serialize();
                const isActive = $('#acad_year_is_active').is(':checked') ? '1' : '0';
                formData = formData.replace(/&?is_active=[^&]*/, '');
                formData += (formData ? '&' : '') + 'is_active=' + isActive;

                $.ajax({
                    url: '<?= base_url('school-setup/saveAcademicYear') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                        }
                        
                        if (response.success) {
                            alert(response.message);
                            closeAcademicYearModal();
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred. Please try again.';
                        
                        // Update CSRF token if provided in error response
                        if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', xhr.responseJSON.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(xhr.responseJSON.csrf_hash);
                        }
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.status === 403 || (xhr.responseText && (xhr.responseText.includes('not allowed') || xhr.responseText.includes('CSRF')))) {
                            errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                        }
                        alert(errorMsg);
                    }
                });
            });

            // Save Semester
            $('#semesterForm').on('submit', function(e) {
                e.preventDefault();
                
                let formData = $('#semesterForm').serialize();
                const isActive = $('#semester_is_active').is(':checked') ? '1' : '0';
                formData = formData.replace(/&?is_active=[^&]*/, '');
                formData += (formData ? '&' : '') + 'is_active=' + isActive;

                $.ajax({
                    url: '<?= base_url('school-setup/saveSemester') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.csrf_hash) {
                            const csrfTokenName = getCSRFTokenName();
                            $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                            $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                        }
                        
                        if (response.success) {
                            alert(response.message);
                            closeSemesterModal();
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                    }
                });
            });

            // Close modals when clicking outside
            window.onclick = function(event) {
                const programModal = document.getElementById('programModal');
                const academicYearModal = document.getElementById('academicYearModal');
                const semesterModal = document.getElementById('semesterModal');
                
                if (event.target == programModal) {
                    closeProgramModal();
                }
                if (event.target == academicYearModal) {
                    closeAcademicYearModal();
                }
                if (event.target == semesterModal) {
                    closeSemesterModal();
                }
            }
        }); // End of $(document).ready
    </script>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

