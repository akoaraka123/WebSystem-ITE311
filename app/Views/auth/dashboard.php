<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Learning Management System</title>
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
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
            width: 100%;
            position: fixed;
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --warning-gradient: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
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
        .card { transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .notification-badge { 
            top: -8px; 
            right: -8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        
        .lms-header {
            background: var(--primary-gradient);
        }
        
        .stat-card {
            background: var(--primary-gradient);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: scale(1.05);
        }
        
        .course-card {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .course-card:hover {
            border-left-color: #4F46E5;
        }
        
        .enrollment-btn {
            background: var(--success-gradient);
            transition: all 0.3s ease;
        }
        
        .enrollment-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
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
        
        .welcome-card {
            background: var(--primary-gradient);
        }
        
        .material-item {
            transition: all 0.2s ease;
        }
        
        .material-item:hover {
            background-color: #F3F4F6;
            transform: translateX(4px);
        }
        
        #notifBell {
            position: relative;
            z-index: 9999;
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        
        #notifDropdown {
            z-index: 9998;
        }
        
        .notification-bell-container {
            position: relative;
            z-index: 9999;
        }

        body.student-theme, body.admin-theme, body.teacher-theme {
            background: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        body.student-theme .welcome-card, body.admin-theme .welcome-card, body.teacher-theme .welcome-card {
            background: #4a90e2;
            border: 3px solid #333;
            border-radius: 3px;
        }

        body.student-theme .course-card, body.admin-theme .course-card, body.teacher-theme .course-card {
            border: 2px solid #999;
            background: #fff;
            border-radius: 3px;
            box-shadow: 3px 3px 8px rgba(0,0,0,0.1);
        }

        body.student-theme .course-card:hover, body.admin-theme .course-card:hover, body.teacher-theme .course-card:hover {
            border-color: #1976d2;
        }

        body.student-theme .stat-card, body.admin-theme .stat-card, body.teacher-theme .stat-card {
            background: #fff;
            color: #333;
            border: 2px solid #999;
            border-radius: 3px;
        }

        body.student-theme .student-badge, body.admin-theme .student-badge, body.teacher-theme .student-badge {
            background: #e3f2fd;
            color: #1976d2;
            font-weight: bold;
            border: 2px solid #90caf9;
            padding: 4px 12px;
            border-radius: 3px;
        }

        body.student-theme button, body.student-theme .btn,
        body.admin-theme button, body.admin-theme .btn,
        body.teacher-theme button, body.teacher-theme .btn {
            border: 2px solid;
            font-weight: bold;
            border-radius: 3px;
        }

        body.student-theme .sidebar-item, body.admin-theme .sidebar-item, body.teacher-theme .sidebar-item {
            border-radius: 3px;
        }

        body.student-theme .lms-header, body.admin-theme .lms-header, body.teacher-theme .lms-header {
            background: #1976d2;
            border-bottom: 2px solid #1565c0;
        }
    </style>
</head>
<body class="bg-gray-50 <?= session('role') === 'student' ? 'student-theme' : (session('role') === 'admin' ? 'admin-theme' : (session('role') === 'teacher' ? 'teacher-theme' : '')) ?>">
    <!-- Sidebar -->
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
                        <?php elseif(session('role') == 'teacher'): ?>
                            <div class="mt-4 mb-2">
                                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Teaching</p>
                            </div>
                            
                            <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'my-courses' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'my-courses' ? '' : 'text-gray-500' ?> fas fa-book-reader"></i>
                                My Courses
                            </a>
                        <?php elseif(session('role') == 'student'): ?>
                            <div class="mt-4 mb-2">
                                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Learning</p>
                            </div>
                            
                            <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'courses' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'courses' ? '' : 'text-gray-500' ?> fas fa-book-open"></i>
                                Browse Courses
                            </a>
                            
                            <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'my-courses' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'my-courses' ? '' : 'text-gray-500' ?> fas fa-graduation-cap"></i>
                                My Learning
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
                            <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>" alt="<?= esc($user['name']) ?>">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700"><?= esc($user['name']) ?></p>
                            <a href="<?= base_url('logout') ?>" class="text-xs text-gray-500 hover:text-primary">Sign out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden" style="margin-left: 256px; width: calc(100% - 256px);">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button class="p-2 text-gray-500 rounded-md md:hidden hover:text-gray-600 hover:bg-gray-100 focus:outline-none">
                            <i class="w-6 h-6 fas fa-bars"></i>
                        </button>
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative notification-bell-container">
                            <button id="notifBell" class="p-2 text-gray-500 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                <i class="w-5 h-5 fas fa-bell"></i>
                                <span id="notifBadge" class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full notification-badge" style="display: none;">0</span>
                            </button>
                            <!-- Notification Dropdown -->
                            <div id="notifDropdown" class="absolute right-0 z-10 hidden w-80 mt-2 origin-top-right bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                        <button id="markAllRead" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Mark all read</button>
                                    </div>
                                </div>
                                <div id="notifList" class="max-h-96 overflow-y-auto">
                                    <!-- Notifications will be loaded here -->
                                </div>
                                <div class="px-4 py-2 text-xs text-center text-gray-500 border-t border-gray-100">
                                    <div class="flex items-center justify-center space-x-2">
                                        <span>Click the bell to refresh</span>
                                        <span>•</span>
                                        <span id="notifTime">Just now</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- User Profile -->
                        <div class="relative ml-3">
                            <div class="flex items-center">
                                <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>" alt="<?= esc($user['name']) ?>">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <?php if (!empty($flash['success'])): ?>
                        <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm"><?= esc($flash['success']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Welcome Card -->
                    <div class="p-8 mb-6 text-white rounded-2xl shadow-xl welcome-card">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-3xl font-bold mb-2">Welcome back, <?= esc($user['name']) ?>! 👋</h2>
                                <p class="text-lg opacity-90">Ready to continue your learning journey?</p>
                                <div class="mt-4 flex items-center space-x-4">
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-white bg-opacity-20 rounded-full">
                                        <i class="mr-2 fas fa-calendar-alt"></i>
                                        <?= date('l, F j, Y') ?>
                                    </span>
                                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium bg-white bg-opacity-20 rounded-full">
                                        <i class="mr-2 fas fa-clock"></i>
                                        <?= date('h:i:s A') ?> (PH Time)
                                    </span>
                                </div>
                            </div>
                            <div class="mt-6 md:mt-0">
                                <div class="text-center">
                                    <i class="fas fa-graduation-cap text-6xl mb-4 opacity-80"></i>
                                    <p class="text-sm opacity-90">Level up your skills today!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STUDENT DASHBOARD -->
                    <?php if ($user['role'] === 'student'): ?>
                        <!-- Search and Filter Section -->
                        <div class="mb-6 bg-white rounded-lg shadow p-4">
                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Search Courses</label>
                                <div class="relative">
                                    <input type="text" 
                                           id="searchCourseInput" 
                                           placeholder="Search by course name, description, or code..." 
                                           class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                                           oninput="setTimeout(function(){if(typeof window.filterCourses === 'function') window.filterCourses();}, 10);"
                                           onkeyup="if(typeof window.filterCourses === 'function') window.filterCourses();"
                                           onpaste="setTimeout(function(){if(typeof window.filterCourses === 'function') window.filterCourses();}, 10);"
                                           autocomplete="off">
                                    <i class="absolute left-3 top-3 text-gray-400 fas fa-search"></i>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">School Year</label>
                                    <select id="filterSchoolYear" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">All School Years</option>
                                        <?php
                                        $currentYear = date('Y');
                                        for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
                                            $nextYear = $i + 1;
                                            echo '<option value="' . $i . '-' . $nextYear . '">' . $i . '-' . $nextYear . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[200px]">
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Semester</label>
                                    <select id="filterSemester" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                        <option value="">All Semesters</option>
                                        <option value="1st Semester">1st Semester</option>
                                        <option value="2nd Semester">2nd Semester</option>
                                        <option value="Summer">Summer</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button onclick="clearFilters()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                        <i class="mr-2 fas fa-redo"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Enrollment Requests -->
                        <?php if (!empty($pending_enrollments ?? [])): ?>
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">Pending Enrollment Requests</h3>
                                <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                    <span id="pendingCount"><?= count($pending_enrollments) ?></span> Pending
                                </span>
                            </div>
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="pendingEnrollmentsContainer">
                                <?php foreach ($pending_enrollments as $pending): ?>
                                    <div class="overflow-hidden bg-white rounded-lg shadow border-2 border-yellow-200">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <?= esc($pending['title'] ?? 'Untitled Course') ?>
                                                </h3>
                                                <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                                    Pending
                                                </span>
                                            </div>
                                            
                                            <?php if (!empty($pending['description'])): ?>
                                                <p class="mt-2 text-sm text-gray-600">
                                                    <?= esc($pending['description']) ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($pending['teacher_name'])): ?>
                                                <p class="mt-2 text-xs text-gray-500">
                                                    <i class="mr-1 fas fa-chalkboard-teacher"></i>
                                                    Teacher: <?= esc($pending['teacher_name']) ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <div class="mt-4">
                                                <?php 
                                                    // Check if enrollment is from admin or teacher
                                                    // Admin-initiated: admin_approved = 1, teacher_approved = 0
                                                    // Teacher-initiated: teacher_approved = 1, admin_approved = 0
                                                    // Student self-enrolled: both = 0
                                                    $isAdminInitiated = !empty($pending['admin_approved']) && $pending['admin_approved'] == 1 && (empty($pending['teacher_approved']) || $pending['teacher_approved'] == 0);
                                                    $isTeacherInitiated = !empty($pending['teacher_approved']) && $pending['teacher_approved'] == 1 && (empty($pending['admin_approved']) || $pending['admin_approved'] == 0);
                                                ?>
                                                
                                                <?php if ($isAdminInitiated): ?>
                                                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                                        <p class="text-sm text-blue-800 text-center">
                                                            <i class="mr-2 fas fa-user-shield"></i>
                                                            Enrollment request from Administrator
                                                        </p>
                                                    </div>
                                                <?php elseif ($isTeacherInitiated): ?>
                                                    <div class="mb-3 p-3 bg-purple-50 border border-purple-200 rounded-md">
                                                        <p class="text-sm text-purple-800 text-center">
                                                            <i class="mr-2 fas fa-chalkboard-teacher"></i>
                                                            Enrollment request from Teacher
                                                        </p>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                                                        <p class="text-sm text-yellow-800 text-center">
                                                            <i class="mr-2 fas fa-clock"></i>
                                                            Waiting for teacher approval
                                                        </p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($isAdminInitiated || $isTeacherInitiated): ?>
                                                    <!-- Show accept/reject buttons for admin/teacher initiated enrollments -->
                                                    <div class="flex gap-2">
                                                        <button onclick="acceptEnrollment(<?= $pending['id'] ?>)" 
                                                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                                            <i class="mr-1 fas fa-check"></i> Accept
                                                        </button>
                                                        <button onclick="rejectEnrollment(<?= $pending['id'] ?>)" 
                                                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                                            <i class="mr-1 fas fa-times"></i> Reject
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">My Enrolled Courses</h3>
                                <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                    <span id="enrolledCount"><?= !empty($enrolled) ? count($enrolled) : 0 ?></span> Enrolled
                                </span>
                            </div>

                            <?php if (!empty($enrolled)): ?>
                                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="enrolledCoursesContainer">
                                    <?php foreach ($enrolled as $enrollment): ?>
                                        <div class="overflow-hidden bg-white rounded-lg shadow course-card course-item" 
                                             data-school-year="<?= esc($enrollment['acad_year_name'] ?? '') ?>"
                                             data-semester="<?= esc($enrollment['semester_name'] ?? '') ?>"
                                             data-course-code="<?= esc($enrollment['course_number'] ?? '') ?>">
                                            <div class="p-6">
                                                <div class="flex items-center justify-between mb-3">
                                                    <h3 class="text-lg font-semibold text-gray-900 course-title">
                                                        <?= esc($enrollment['title'] ?? 'Untitled Course') ?>
                                                    </h3>
                                                    <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                                                        Enrolled
                                                    </span>
                                                </div>
                                                
                                                <?php if (!empty($enrollment['description'])): ?>
                                                    <p class="mt-2 text-sm text-gray-600 mb-3">
                                                        <?= esc($enrollment['description']) ?>
                                                    </p>
                                                <?php endif; ?>
                                                
                                                <!-- Academic Information Section -->
                                                <div class="bg-gray-50 rounded-lg p-3 mb-3 border border-gray-200">
                                                    <div class="space-y-2">
                                                        <?php if (!empty($enrollment['course_number'])): ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-hashtag mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Code:</span>
                                                            <span class="ml-2"><?= esc($enrollment['course_number']) ?></span>
                                                        </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($enrollment['acad_year_name'])): ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-calendar-alt mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Academic Year:</span>
                                                            <span class="ml-2"><?= esc($enrollment['acad_year_name']) ?></span>
                                                        </div>
                                                        <?php endif; ?>

                                                        <?php if (!empty($enrollment['semester_name'])): ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-calendar-week mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Semester:</span>
                                                            <span class="ml-2"><?= esc($enrollment['semester_name']) ?></span>
                                                        </div>
                                                        <?php endif; ?>

                                                        <?php 
                                                        $startTime = $enrollment['schedule_time_start'] ?? $enrollment['schedule_time'] ?? '';
                                                        $endTime = $enrollment['schedule_time_end'] ?? '';
                                                        if ($startTime || !empty($enrollment['schedule_date'])): 
                                                        ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-clock mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Schedule:</span>
                                                            <span class="ml-2">
                                                                <?php if ($startTime): ?>
                                                                    <?php 
                                                                    $startFormatted = date('g:i A', strtotime($startTime));
                                                                    if ($endTime) {
                                                                        $endFormatted = date('g:i A', strtotime($endTime));
                                                                        echo esc($startFormatted) . ' - ' . esc($endFormatted);
                                                                    } else {
                                                                        echo esc($startFormatted);
                                                                    }
                                                                    ?>
                                                                <?php endif; ?>
                                                                <?php if (!empty($enrollment['schedule_date'])): ?>
                                                                    <?php if ($startTime): ?>, <?php endif; ?>
                                                                    <?= date('M d, Y', strtotime($enrollment['schedule_date'])) ?>
                                                                <?php endif; ?>
                                                            </span>
                                                        </div>
                                                        <?php endif; ?>

                                                        <?php 
                                                        // Calculate exact duration from start and end times
                                                        $startTime = $enrollment['schedule_time_start'] ?? $enrollment['schedule_time'] ?? '';
                                                        $endTime = $enrollment['schedule_time_end'] ?? '';
                                                        if ($startTime && $endTime): 
                                                            $startTimestamp = strtotime($startTime);
                                                            $endTimestamp = strtotime($endTime);
                                                            $diffMinutes = round(($endTimestamp - $startTimestamp) / 60);
                                                            $hours = floor($diffMinutes / 60);
                                                            $minutes = $diffMinutes % 60;
                                                        ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-hourglass-half mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Duration:</span>
                                                            <span class="ml-2">
                                                                <?php 
                                                                if ($hours > 0 && $minutes > 0) {
                                                                    echo esc($hours) . ' hour' . ($hours > 1 ? 's' : '') . ' ' . esc($minutes) . ' minute' . ($minutes > 1 ? 's' : '');
                                                                } else if ($hours > 0) {
                                                                    echo esc($hours) . ' hour' . ($hours > 1 ? 's' : '');
                                                                } else {
                                                                    echo esc($minutes) . ' minute' . ($minutes > 1 ? 's' : '');
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                        <?php elseif (!empty($enrollment['duration'])): ?>
                                                        <div class="flex items-center text-xs text-gray-700">
                                                            <i class="fas fa-hourglass-half mr-2 text-gray-500 w-3"></i>
                                                            <span class="font-medium">Duration:</span>
                                                            <span class="ml-2"><?= esc($enrollment['duration']) ?> <?= $enrollment['duration'] == 1 ? 'Hour' : 'Hours' ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- View Enrolled Students Button -->
                                                <div class="mt-4 mb-4">
                                                    <button type="button" 
                                                            onclick="openEnrolledStudentsModal(<?= esc($enrollment['course_id']) ?>, '<?= esc($enrollment['title'], 'attr') ?>')"
                                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                        <i class="mr-2 fas fa-users"></i> View Enrolled Students
                                                    </button>
                                                </div>

                                                <div class="mt-4 course-materials">
                                                    <h4 class="mb-3 text-sm font-semibold text-gray-800">Modules</h4>
                                                    
                                                    <?php
                                                    // Get course materials
                                                    $courseMaterials = $materials[$enrollment['course_id']] ?? [];
                                                    
                                                    // Get terms for this course's semester
                                                    $termModel = new \App\Models\TermModel();
                                                    $courseSemesterId = $enrollment['semester_id'] ?? null;
                                                    
                                                    // Get terms from database if semester_id is available
                                                    if ($courseSemesterId) {
                                                        $terms = $termModel->getTermsBySemester($courseSemesterId);
                                                    }
                                                    
                                                    // If no terms found or no semester_id, use default terms
                                                    if (empty($terms)) {
                                                        $terms = [
                                                            ['id' => 1, 'term_name' => 'Prelim', 'term_order' => 1],
                                                            ['id' => 2, 'term_name' => 'Midterm', 'term_order' => 2],
                                                            ['id' => 3, 'term_name' => 'Finals', 'term_order' => 3]
                                                        ];
                                                    }
                                                    ?>
                                                    
                                                    <div class="space-y-3">
                                                        <?php 
                                                        // Separate materials with and without term_id
                                                        $materialsWithTerm = [];
                                                        $materialsWithoutTerm = [];
                                                        
                                                        foreach ($courseMaterials as $material) {
                                                            $materialTermId = $material['term_id'] ?? null;
                                                            // Check if term_id is null, empty, or 0
                                                            if (empty($materialTermId) || $materialTermId === '0' || $materialTermId === 0) {
                                                                $materialsWithoutTerm[] = $material;
                                                            } else {
                                                                $materialsWithTerm[(int)$materialTermId][] = $material;
                                                            }
                                                        }
                                                        ?>
                                                        
                                                        <?php foreach ($terms as $index => $term): ?>
                                                            <?php
                                                            $termId = 'term-' . $enrollment['course_id'] . '-' . ($term['id'] ?? $term['term_order']);
                                                            $termName = strtoupper($term['term_name']);
                                                            $termDbId = isset($term['id']) ? (int)$term['id'] : null;
                                                            
                                                            // Get materials for this term
                                                            $termMaterials = [];
                                                            if ($termDbId !== null) {
                                                                $termMaterials = $materialsWithTerm[$termDbId] ?? [];
                                                            }
                                                            
                                                            // Also include materials without term_id in the first term (PRELIM) as fallback
                                                            if ($index === 0 && !empty($materialsWithoutTerm)) {
                                                                $termMaterials = array_merge($termMaterials, $materialsWithoutTerm);
                                                            }
                                                            ?>
                                                            
                                                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                                                <!-- Term Header (Expandable) -->
                                                                <button type="button" 
                                                                        onclick="toggleTermModule('<?= $termId ?>')"
                                                                        class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition-colors border-b border-gray-200">
                                                                    <div class="flex items-center">
                                                                        <i class="fas fa-chevron-down mr-3 text-gray-500 term-arrow-<?= $termId ?>" id="arrow-<?= $termId ?>"></i>
                                                                        <span class="font-semibold text-gray-800"><?= esc($termName) ?></span>
                                                                    </div>
                                                                    <span class="text-xs text-gray-500">
                                                                        <?= count($termMaterials) ?> item<?= count($termMaterials) != 1 ? 's' : '' ?>
                                                                    </span>
                                                                </button>
                                                                
                                                                <!-- Term Content (Collapsible) -->
                                                                <div id="content-<?= $termId ?>" class="hidden bg-white">
                                                                    <div class="p-4 space-y-2">
                                                                        <?php if (!empty($termMaterials)): ?>
                                                                            <?php foreach ($termMaterials as $material): ?>
                                                                                <div class="flex items-center justify-between p-3 text-sm bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                                                                                    <div class="flex items-center flex-1">
                                                                                        <i class="mr-3 text-blue-500 fas fa-file-pdf"></i>
                                                                                        <div class="flex-1">
                                                                                            <a href="<?= base_url('materials/download/'.($material['id'] ?? '')) ?>" 
                                                                                               class="font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                                                                <?= esc($material['file_name'] ?? 'Untitled File') ?>
                                                                                            </a>
                                                                                            <div class="text-xs text-gray-500 mt-1">
                                                                                                <i class="far fa-clock mr-1"></i>
                                                                                                <?= date('M j, Y', strtotime($material['created_at'])) ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <a href="<?= base_url('materials/download/'.($material['id'] ?? '')) ?>" 
                                                                                       class="ml-3 p-2 text-blue-600 rounded-full hover:bg-blue-50 transition-colors"
                                                                                       title="Download">
                                                                                        <i class="w-4 h-4 fas fa-download"></i>
                                                                                    </a>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        <?php else: ?>
                                                                            <p class="py-4 text-sm text-center text-gray-500">
                                                                                <i class="mr-2 far fa-folder-open"></i> No materials available yet
                                                                            </p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="p-8 text-center bg-white rounded-lg shadow">
                                    <i class="mx-auto text-4xl text-gray-300 fas fa-book-open"></i>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Enrolled Courses</h3>
                                    <p class="mt-1 text-gray-500">You haven't enrolled in any courses yet.</p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($available ?? [])): ?>
                                <div class="mt-8">
                                    <h3 class="mb-4 text-xl font-semibold text-gray-800">Available Courses</h3>
                                    <div class="grid gap-4">
                                        <?php foreach ($available as $course): ?>
                                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h4>
                                                    <!-- School Year and Semester Info -->
                                                    <div class="mt-2 flex gap-2 flex-wrap">
                                                        <?php if (!empty($course['school_year'])): ?>
                                                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded">
                                                                <i class="mr-1 fas fa-calendar"></i> <?= esc($course['school_year']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($course['semester'])): ?>
                                                            <span class="px-2 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded">
                                                                <i class="mr-1 fas fa-calendar-alt"></i> <?= esc($course['semester']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if (!empty($course['description'])): ?>
                                                        <p class="mt-1 text-sm text-gray-600"><?= esc($course['description']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <button type="button" 
                                                        class="px-6 py-3 text-sm font-medium text-white rounded-lg enrollment-btn btn-enroll" 
                                                        data-course-id="<?= isset($course['id']) ? esc($course['id']) : '0' ?>">
                                                    <i class="mr-2 fas fa-user-plus"></i>
                                                    Enroll Now
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mt-8">
                                    <h3 class="mb-4 text-xl font-semibold text-gray-800">Available Courses</h3>
                                    <div class="p-8 text-center bg-white rounded-lg shadow">
                                        <i class="mx-auto text-4xl text-gray-300 fas fa-search"></i>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Available Courses</h3>
                                        <p class="mt-1 text-gray-500">All courses are already enrolled or no courses exist.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- User Profile Card -->
                    <div class="p-6 mb-8 bg-white rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="w-16 h-16 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>" alt="">
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900"><?= esc($user['name']) ?></h3>
                                <p class="text-sm text-gray-500"><?= esc($user['email']) ?></p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <?= ucfirst(esc($user['role'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="px-4 py-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">User ID</p>
                                    <p class="text-sm font-medium text-gray-900"><?= esc($user['id']) ?></p>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Account Created</p>
                                    <p class="text-sm font-medium text-gray-900"><?= date('M j, Y', strtotime($user['created_at'] ?? 'now')) ?></p>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-500">Last Login</p>
                                    <p class="text-sm font-medium text-gray-900"><?= $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- TEACHER DASHBOARD -->
        <?php if ($user['role'] === 'teacher'): ?>
            <!-- Filter Section -->
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-2 text-sm font-medium text-gray-700">School Year</label>
                        <select id="filterSchoolYearTeacher" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">All School Years</option>
                            <?php
                            $currentYear = date('Y');
                            for ($i = $currentYear - 2; $i <= $currentYear + 2; $i++) {
                                $nextYear = $i + 1;
                                echo '<option value="' . $i . '-' . $nextYear . '">' . $i . '-' . $nextYear . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Semester</label>
                        <select id="filterSemesterTeacher" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">All Semesters</option>
                            <option value="1st Semester">1st Semester</option>
                            <option value="2nd Semester">2nd Semester</option>
                            <option value="Summer">Summer</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="clearFiltersTeacher()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <i class="mr-2 fas fa-redo"></i> Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pending Enrollment Requests for Teacher -->
            <?php if (!empty($pending_enrollments ?? [])): ?>
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Pending Enrollment Requests</h3>
                    <span class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full">
                        <span id="pendingCountTeacher"><?= count($pending_enrollments) ?></span> Pending
                    </span>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="pendingEnrollmentsTeacherContainer">
                    <?php foreach ($pending_enrollments as $pending): ?>
                        <div class="overflow-hidden bg-white rounded-lg shadow border-2 border-yellow-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?= esc($pending['course_title'] ?? 'Untitled Course') ?>
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                        Pending
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-700">
                                        <i class="mr-2 fas fa-user-graduate"></i>
                                        Student: <?= esc($pending['student_name'] ?? 'Unknown') ?>
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-envelope"></i>
                                        <?= esc($pending['student_email'] ?? '') ?>
                                    </p>
                                    <p class="mt-2 text-xs text-gray-500">
                                        <i class="mr-1 fas fa-calendar"></i>
                                        Requested: <?= date('M d, Y', strtotime($pending['enrollment_date'])) ?>
                                    </p>
                                </div>
                                
                                <div class="flex gap-2 mt-4">
                                    <button onclick="teacherApproveEnrollment(<?= $pending['id'] ?>, <?= $pending['course_id'] ?>)" 
                                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                        <i class="mr-1 fas fa-check"></i> Approve
                                    </button>
                                    <button onclick="teacherRejectEnrollment(<?= $pending['id'] ?>, <?= $pending['course_id'] ?>)" 
                                            class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">
                                        <i class="mr-1 fas fa-times"></i> Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Enrollment Statistics Summary -->
            <?php 
                $totalAccepted = 0;
                $totalPending = 0;
                $totalCourses = count($myCourses ?? []);
                if (isset($enrollmentStats) && is_array($enrollmentStats)) {
                    foreach ($enrollmentStats as $stats) {
                        $totalAccepted += $stats['accepted'] ?? 0;
                        $totalPending += $stats['pending'] ?? 0;
                    }
                }
            ?>
            <div class="grid gap-4 mb-8 md:grid-cols-3">
                <div class="p-6 bg-white rounded-lg shadow stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Courses</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><?= $totalCourses ?></p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="text-2xl text-blue-600 fas fa-book"></i>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white rounded-lg shadow stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Accepted Enrollments</p>
                            <p class="mt-2 text-3xl font-bold text-green-600"><?= $totalAccepted ?></p>
                            <p class="mt-1 text-xs text-gray-500">Students enrolled</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="text-2xl text-green-600 fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-white rounded-lg shadow stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                            <p class="mt-2 text-3xl font-bold text-yellow-600"><?= $totalPending ?></p>
                            <p class="mt-1 text-xs text-gray-500">Awaiting acceptance</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="text-2xl text-yellow-600 fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Courses</h2>
                    <p class="text-sm text-gray-600">
                        <i class="mr-1 fas fa-info-circle"></i>
                        Courses are assigned by administrators
                    </p>
                </div>

                <?php if (!empty($myCourses)): ?>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="teacherCoursesContainer">
                        <?php foreach ($myCourses as $course): ?>
                            <div class="overflow-hidden bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 course-item-teacher" 
                                 id="course-<?= esc($course['id']) ?>"
                                 data-school-year="<?= esc($course['school_year'] ?? '') ?>"
                                 data-semester="<?= esc($course['semester'] ?? '') ?>">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h3>
                                        <div class="flex items-center gap-2">
                                            <!-- Enrollment Statistics -->
                                            <div class="flex items-center gap-2">
                                                <?php 
                                                    $stats = isset($enrollmentStats) && is_array($enrollmentStats) ? ($enrollmentStats[$course['id']] ?? ['accepted' => 0, 'pending' => 0, 'total' => 0]) : ['accepted' => 0, 'pending' => 0, 'total' => 0];
                                                    $acceptedCount = $stats['accepted'] ?? 0;
                                                    $pendingCount = $stats['pending'] ?? 0;
                                                ?>
                                                <div class="flex flex-col gap-1">
                                                    <div class="flex items-center gap-1">
                                                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded" title="Accepted Enrollments">
                                                            <i class="mr-1 fas fa-check-circle"></i><?= $acceptedCount ?> Accepted
                                                        </span>
                                                        <?php if ($pendingCount > 0): ?>
                                                        <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded" title="Pending Enrollments">
                                                            <i class="mr-1 fas fa-clock"></i><?= $pendingCount ?> Pending
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php if ($acceptedCount > 0 || $pendingCount > 0): ?>
                                                    <div class="text-xs text-center text-gray-600 font-medium">
                                                        Total: <?= $acceptedCount + $pendingCount ?> Students
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <form action="<?= base_url('course/delete/' . $course['id']) ?>" method="POST" style="display: inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this course? This will also delete all materials and enrollments.')"
                                                        class="p-2 text-red-600 rounded-full hover:bg-red-50 transition-colors duration-200"
                                                        title="Delete course">
                                                    <i class="w-4 h-4 fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- Course Information Section -->
                                    <div class="mb-4 bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                            <i class="mr-1 fas fa-info-circle"></i> Course Information
                                        </h4>
                                        <div class="space-y-1.5 text-xs">
                                            <?php if (!empty($course['course_number'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-hashtag"></i>
                                                    <span class="font-medium">Course Code:</span>
                                                    <span class="ml-1"><?= esc($course['course_number']) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['acad_year_name'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-calendar"></i>
                                                    <span class="font-medium">Academic Year:</span>
                                                    <span class="ml-1"><?= esc($course['acad_year_name']) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['semester_name'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-calendar-alt"></i>
                                                    <span class="font-medium">Semester:</span>
                                                    <span class="ml-1"><?= esc($course['semester_name']) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['schedule_time_start']) && !empty($course['schedule_time_end'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-clock"></i>
                                                    <span class="font-medium">Time:</span>
                                                    <span class="ml-1">
                                                        <?php
                                                            $startTime = date('g:i A', strtotime($course['schedule_time_start']));
                                                            $endTime = date('g:i A', strtotime($course['schedule_time_end']));
                                                            echo esc($startTime . ' - ' . $endTime);
                                                            
                                                            // Calculate duration
                                                            if (!empty($course['schedule_time_start']) && !empty($course['schedule_time_end'])) {
                                                                $start = new \DateTime($course['schedule_time_start']);
                                                                $end = new \DateTime($course['schedule_time_end']);
                                                                $diff = $start->diff($end);
                                                                $hours = $diff->h;
                                                                $minutes = $diff->i;
                                                                
                                                                $durationText = '';
                                                                if ($hours > 0) {
                                                                    $durationText .= $hours . ' hour' . ($hours > 1 ? 's' : '');
                                                                }
                                                                if ($minutes > 0) {
                                                                    if ($hours > 0) $durationText .= ' ';
                                                                    $durationText .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
                                                                }
                                                                if (!empty($durationText)) {
                                                                    echo ' | Duration: ' . $durationText;
                                                                }
                                                            }
                                                        ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['schedule_date'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 far fa-calendar"></i>
                                                    <span class="font-medium">Date:</span>
                                                    <span class="ml-1"><?= date('M j, Y', strtotime($course['schedule_date'])) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['created_at'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-calendar-plus"></i>
                                                    <span class="font-medium">Created:</span>
                                                    <span class="ml-1"><?= date('M j, Y', strtotime($course['created_at'])) ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['teacher_name']) || !empty($course['teacher_user_id'])): ?>
                                                <div class="flex items-center text-gray-700">
                                                    <i class="mr-2 w-4 text-gray-500 fas fa-user-tie"></i>
                                                    <span class="font-medium">Teacher:</span>
                                                    <span class="ml-1">
                                                        <?= esc($course['teacher_name'] ?? 'Unknown') ?>
                                                        <?php if (!empty($course['teacher_user_id'])): ?>
                                                            <span class="text-gray-500">(ID: <?= esc($course['teacher_user_id']) ?>)</span>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($course['description'])): ?>
                                        <p class="mb-4 text-sm text-gray-600"><?= esc($course['description']) ?></p>
                                    <?php endif; ?>

                                    <!-- Upload Form -->
                                    <form class="mb-4 uploadForm" data-course-id="<?= esc($course['id']) ?>" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="mb-2">
                                            <label class="block mb-1 text-sm font-medium text-gray-700">Upload Material</label>
                                            <input type="file" name="material" required
                                                class="block w-full text-sm text-gray-500
                                                       file:mr-4 file:py-2 file:px-4
                                                       file:rounded-md file:border-0
                                                       file:text-sm file:font-medium
                                                       file:bg-blue-50 file:text-blue-700
                                                       hover:file:bg-blue-100">
                                        </div>
                                        <div class="mb-2">
                                            <label class="block mb-1 text-sm font-medium text-gray-700">Term</label>
                                            <select name="term_id" id="term_id_<?= esc($course['id']) ?>" required
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                                <option value="">Select Term</option>
                                                <?php
                                                // Get terms for this course's semester
                                                $termModel = new \App\Models\TermModel();
                                                $courseSemesterId = $course['semester_id'] ?? null;
                                                if ($courseSemesterId) {
                                                    $terms = $termModel->getTermsBySemester($courseSemesterId);
                                                    foreach ($terms as $term) {
                                                        echo '<option value="' . esc($term['id']) . '">' . esc(strtoupper($term['term_name'])) . '</option>';
                                                    }
                                                } else {
                                                    // Fallback: show standard terms if semester_id is not available
                                                    echo '<option value="">PRELIM</option>';
                                                    echo '<option value="">MIDTERM</option>';
                                                    echo '<option value="">FINAL</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="uploadMessage text-xs text-center"></div>
                                        <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="mr-2 fas fa-upload"></i> Upload
                                        </button>
                                    </form>

                                    <!-- Action Buttons -->
                                    <div class="mb-4 space-y-2">
                                        <button type="button" 
                                                onclick="openAddStudentModal(<?= esc($course['id']) ?>, '<?= esc($course['title'], 'attr') ?>')"
                                                class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="mr-2 fas fa-user-plus"></i> Add Student
                                        </button>
                                        <button type="button" 
                                                onclick="openEnrollmentDetailsModal(<?= esc($course['id']) ?>, '<?= esc($course['title'], 'attr') ?>')"
                                                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="mr-2 fas fa-users"></i> View Enrollments
                                        </button>
                                    </div>

                                    <!-- Course Materials -->
                                    <div class="course-materials">
                                        <h4 class="mb-2 text-sm font-medium text-gray-700">Course Materials</h4>
                                        <?php if (!empty($materials[$course['id']])): ?>
                                            <ul class="space-y-2">
                                                <?php foreach ($materials[$course['id']] as $mat): ?>
                                                    <li class="flex items-center justify-between p-3 text-sm bg-gray-50 rounded-md">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-1">
                                                                <i class="mr-2 text-gray-500 fas fa-file-alt"></i>
                                                                <span class="font-medium"><?= esc($mat['file_name']) ?></span>
                                                            </div>
                                                            <div class="text-xs text-gray-500 ml-6">
                                                                <i class="far fa-clock mr-1"></i>
                                                                <?= date('F j, Y - h:i:s A', strtotime($mat['created_at'])) ?>
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-2">
                                                            <a href="<?= base_url('materials/download/'.$mat['id']) ?>" 
                                                               class="p-1 text-blue-600 rounded-full hover:bg-blue-50"
                                                               title="Download">
                                                                <i class="w-4 h-4 fas fa-download"></i>
                                                            </a>
                                                            <button class="p-1 text-red-600 rounded-full hover:bg-red-50 delete-material" 
                                                                    data-material-id="<?= $mat['id'] ?>"
                                                                    title="Delete">
                                                                <i class="w-4 h-4 fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p class="py-4 text-sm text-center text-gray-500 bg-gray-50 rounded-md">
                                                <i class="mr-2 far fa-folder-open"></i> No materials uploaded yet
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center bg-white rounded-lg shadow">
                        <i class="mx-auto text-4xl text-gray-300 fas fa-chalkboard-teacher"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No courses yet</h3>
                        <p class="mt-1 text-gray-500">Courses are assigned by administrators. Contact admin to get courses assigned to you.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ADMIN DASHBOARD -->
        <?php if ($user['role'] === 'admin'): ?>
            <!-- Statistics Cards -->
            <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow stat-card border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><?= $totalUsers ?? 0 ?></p>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <span class="mr-3"><i class="fas fa-user-graduate mr-1"></i><?= $totalStudents ?? 0 ?> Students</span>
                                <span class="mr-3"><i class="fas fa-chalkboard-teacher mr-1"></i><?= $totalTeachers ?? 0 ?> Teachers</span>
                                <span><i class="fas fa-user-shield mr-1"></i><?= $totalAdmins ?? 0 ?> Admins</span>
                            </div>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="text-2xl text-blue-600 fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('users') ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Manage Users <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow stat-card border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Courses</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><?= $totalCourses ?? 0 ?></p>
                            <p class="mt-1 text-xs text-gray-500">Active courses in system</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="text-2xl text-green-600 fas fa-book"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('courses') ?>" class="text-sm text-green-600 hover:text-green-800 font-medium">
                            Manage Courses <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow stat-card border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Enrollments</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><?= $totalEnrollments ?? 0 ?></p>
                            <p class="mt-1 text-xs text-gray-500">Student enrollments</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <i class="text-2xl text-purple-600 fas fa-user-check"></i>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-lg shadow stat-card border-l-4 border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Programs</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900"><?= $totalPrograms ?? 0 ?></p>
                            <p class="mt-1 text-xs text-gray-500">Active programs</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-full">
                            <i class="text-2xl text-orange-600 fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="<?= base_url('school-setup') ?>" class="text-sm text-orange-600 hover:text-orange-800 font-medium">
                            View Programs <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- School Settings Info -->
            <?php if (!empty($activeSchoolSettings ?? [])): ?>
            <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                            Current School Year & Semester
                        </h3>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-600">School Year</p>
                                <p class="text-lg font-bold text-gray-900"><?= esc($activeSchoolSettings['school_year']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Semester</p>
                                <p class="text-lg font-bold text-gray-900"><?= esc($activeSchoolSettings['semester']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Start Date</p>
                                <p class="text-base font-semibold text-gray-900"><?= date('M d, Y', strtotime($activeSchoolSettings['start_date'])) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">End Date</p>
                                <p class="text-base font-semibold text-gray-900"><?= date('M d, Y', strtotime($activeSchoolSettings['end_date'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="<?= base_url('school-setup') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-cog mr-2"></i>
                            Configure
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="mb-8 p-6 bg-yellow-50 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                            School Settings Not Configured
                        </h3>
                        <p class="text-sm text-gray-600">Please configure school year, semester, and dates to get started.</p>
                    </div>
                    <div class="ml-4">
                        <a href="<?= base_url('school-setup') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-cog mr-2"></i>
                            Setup Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Quick Actions
                </h3>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <a href="<?= base_url('users') ?>" class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-blue-500">
                        <div class="flex-shrink-0 p-3 bg-blue-100 rounded-lg">
                            <i class="text-2xl text-blue-600 fas fa-user-plus"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Add New User</p>
                            <p class="text-xs text-gray-500">Create student/teacher</p>
                        </div>
                    </a>


                    <a href="<?= base_url('school-setup') ?>" class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-purple-500">
                        <div class="flex-shrink-0 p-3 bg-purple-100 rounded-lg">
                            <i class="text-2xl text-purple-600 fas fa-cog"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">School Setup</p>
                            <p class="text-xs text-gray-500">Configure settings</p>
                        </div>
                    </a>

                    <a href="<?= base_url('courses') ?>" class="flex items-center p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border-l-4 border-orange-500">
                        <div class="flex-shrink-0 p-3 bg-orange-100 rounded-lg">
                            <i class="text-2xl text-orange-600 fas fa-tasks"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Manage All</p>
                            <p class="text-xs text-gray-500">Courses & Users</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent File Uploads -->
            <div class="mt-8">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-file-upload mr-2 text-gray-500"></i>
                        Recent File Uploads
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">All files uploaded by teachers</p>
                </div>
                
                <?php if (!empty($recentUploads ?? [])): ?>
                    <div class="bg-white rounded-lg shadow">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">File Name</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Uploaded By</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Course</th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recentUploads as $upload): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <i class="mr-2 text-gray-400 fas fa-file-alt"></i>
                                                    <div class="text-sm font-medium text-gray-900"><?= esc($upload['file_name'] ?? 'Untitled File') ?></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= esc($upload['teacher_name'] ?? 'Unknown') ?></div>
                                                <div class="text-sm text-gray-500"><?= esc($upload['teacher_email'] ?? 'unknown@example.com') ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= esc($upload['course_title'] ?? 'Untitled Course') ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                                <?= date('M j, Y g:i A', strtotime($upload['created_at'] ?? 'now')) ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                <a href="<?= base_url('materials/download/'.($upload['id'] ?? '')) ?>" 
                                                   class="text-blue-600 hover:text-blue-900">Download</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-8 text-center bg-white rounded-lg shadow">
                        <i class="mx-auto text-4xl text-gray-300 fas fa-file-upload"></i>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No Recent Uploads</h3>
                        <p class="mt-1 text-gray-500">No files have been uploaded by teachers yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($flash['error'])): ?>
            <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm"><?= esc($flash['error']) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hidden CSRF token for AJAX calls -->
    <?= csrf_field() ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Toggle user dropdown
    $('#user-menu-button').click(function(e) {
        e.stopPropagation();
        $('#user-dropdown').toggleClass('hidden');
    });

    // Close dropdowns when clicking outside
    $(document).click(function() {
        $('#user-dropdown').addClass('hidden');
        $('#notifDropdown').addClass('hidden');
    });

    // AJAX Enroll handler
    $(document).on('click', '.btn-enroll', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        var btn = $(this);
        var courseID = btn.data('course-id');
        
        if (!courseID || courseID === '0' || courseID === undefined) {
            alert('Invalid course ID. Please refresh the page and try again.');
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enrolling...');
        
        var csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
        var csrfName = csrfInput.attr('name');
        var csrfHash = csrfInput.val();
        
        var data = { course_id: courseID };
        if (csrfName && csrfHash) {
            data[csrfName] = csrfHash;
        }
        
        $.ajax({
            url: '<?= base_url('course/enroll') ?>',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.csrf_hash && csrfInput.length) {
                    csrfInput.val(response.csrf_hash);
                }
                
                if (response.success) {
                    btn.removeClass('enrollment-btn').addClass('bg-green-500').prop('disabled', true).html('<i class="fas fa-check"></i> Enrolled!');
                    alert(response.message || 'Enrolled successfully!');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    alert(response.message || 'Enrollment failed');
                    btn.prop('disabled', false).html('<i class="mr-2 fas fa-user-plus"></i> Enroll Now');
                }
            },
            error: function(xhr, status, error) {
                console.error('Enrollment error:', xhr.responseText);
                var errorMsg = 'Request failed';
                if (xhr.status === 404) {
                    errorMsg = 'Enrollment endpoint not found';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error - please try again';
                }
                alert(errorMsg);
                btn.prop('disabled', false).html('<i class="mr-2 fas fa-user-plus"></i> Enroll Now');
            }
        });
    });

    // Upload form handler
    $(document).on('submit', '.uploadForm', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var courseId = form.data('course-id');
        var fileInput = form.find('input[type="file"]');
        var termSelect = form.find('select[name="term_id"]');
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.uploadMessage');
        
        // Check if file is selected
        if (!fileInput[0].files[0]) {
            if (messageDiv.length) {
                messageDiv.text('Please select a file to upload').css('color', 'red');
            } else {
                alert('Please select a file to upload');
            }
            return;
        }
        
        // Check if term is selected
        if (!termSelect.val()) {
            if (messageDiv.length) {
                messageDiv.text('Please select a term (PRELIM, MIDTERM, or FINAL)').css('color', 'red');
            } else {
                alert('Please select a term (PRELIM, MIDTERM, or FINAL)');
            }
            return;
        }
        
        // Create FormData
        var formData = new FormData(form[0]);
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...');
        if (messageDiv.length) {
            messageDiv.text('Uploading file...').css('color', 'blue');
        }
        
        // Get CSRF token
        var csrfInput = form.find('input[name^="<?= csrf_token() ?>"]');
        var csrfName = csrfInput.attr('name');
        var csrfHash = csrfInput.val();
        
        if (csrfName && csrfHash) {
            formData.set(csrfName, csrfHash);
        }
        
        $.ajax({
            url: '<?= base_url('materials/upload_ajax') ?>/' + courseId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Upload response:', response);
                
                // Update CSRF token if provided
                if (response.csrf_hash && csrfInput.length) {
                    csrfInput.val(response.csrf_hash);
                }
                
                if (response.success) {
                    if (messageDiv.length) {
                        messageDiv.text('🎉 Material uploaded successfully!').css('color', 'green');
                    } else {
                        alert('Material uploaded successfully!');
                    }
                    
                    // Reset form
                    form[0].reset();
                    
                    // Reload page after delay to show the new material
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                } else {
                    var errorMsg = response.message || 'Upload failed';
                    if (messageDiv.length) {
                        messageDiv.text('❌ ' + errorMsg).css('color', 'red');
                    } else {
                        alert('Upload failed: ' + errorMsg);
                    }
                }
                
                // Reset button
                submitBtn.prop('disabled', false).html('<i class="mr-2 fas fa-upload"></i> Upload');
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', {
                    status: xhr.status,
                    responseText: xhr.responseText,
                    error: error
                });
                
                var errorMsg = 'Upload failed';
                if (xhr.status === 0) {
                    errorMsg = 'Network error - please check your connection';
                } else if (xhr.status === 404) {
                    errorMsg = 'Upload endpoint not found';
                } else if (xhr.status === 500) {
                    errorMsg = 'Server error - please try again';
                } else if (xhr.responseText) {
                    try {
                        var errorData = JSON.parse(xhr.responseText);
                        errorMsg = errorData.message || errorMsg;
                    } catch (e) {
                        errorMsg = 'Error: ' + xhr.responseText.substring(0, 100);
                    }
                }
                
                if (messageDiv.length) {
                    messageDiv.text('❌ ' + errorMsg).css('color', 'red');
                } else {
                    alert(errorMsg);
                }
                
                // Reset button
                submitBtn.prop('disabled', false).html('<i class="mr-2 fas fa-upload"></i> Upload');
            }
        });
    });

    // Notification variables
    var notifBadge = $('#notifBadge');
    var notifDropdown = $('#notifDropdown');
    var notifList = $('#notifList');

    // Fetch notifications function
    function fetchNotifications(limit) {
        limit = limit || 10;
        $.get('<?= base_url('notifications') ?>', {limit: limit}, function(resp) {
            if (!resp || !resp.success) return;
            
            if (resp.csrf_hash) {
                var csrfInput = $('input[name^="<?= csrf_token() ?>"]');
                csrfInput.val(resp.csrf_hash);
            }
            
            if (resp.unread > 0) {
                notifBadge.text(resp.unread).show();
            } else {
                notifBadge.hide();
            }
            
            var html = '';
            if (resp.items && resp.items.length) {
                for (var i = 0; i < resp.items.length; i++) {
                    var it = resp.items[i];
                    var time = it.created_at || '';
                    var safeMsg = it.message ? $('<div>').text(it.message).html() : '';
                    var notifId = it.id || '';
                    var isRead = it.is_read == 1;
                    
                    var icon = 'fa-bell';
                    var iconColor = 'text-blue-500';
                    var bgColor = 'bg-blue-50';
                    var isClickable = false;
                    var clickAction = '';
                    
                    if (safeMsg.includes('enrolled')) {
                        icon = 'fa-user-plus';
                        iconColor = 'text-green-500';
                        bgColor = 'bg-green-50';
                        isClickable = true;
                        clickAction = 'view-course';
                    } else if (safeMsg.includes('material uploaded') || safeMsg.includes('file')) {
                        icon = 'fa-file-upload';
                        iconColor = 'text-purple-500';
                        bgColor = 'bg-purple-50';
                        isClickable = true;
                        clickAction = 'view-materials';
                    }
                    
                    html += '<div class="flex items-start p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 ' + 
                            (!isRead ? 'bg-blue-50 border-l-4 border-l-blue-500' : '') + 
                            (isClickable ? ' cursor-pointer notif-card' : '') + 
                            '" data-notif-id="' + notifId + '" data-action="' + clickAction + '" data-message="' + safeMsg + '">' +
                        '<div class="flex-shrink-0 w-10 h-10 rounded-full ' + bgColor + ' flex items-center justify-center">' +
                            '<i class="fas ' + icon + ' ' + iconColor + ' text-sm"></i>' +
                        '</div>' +
                        '<div class="ml-3 flex-1 min-w-0">' +
                            '<p class="text-sm font-medium text-gray-900 ' + (!isRead ? 'font-semibold' : '') + '">' + safeMsg + '</p>' +
                            '<p class="text-xs text-gray-500 mt-1">' +
                                '<i class="far fa-clock mr-1"></i>' +
                                time +
                            '</p>' +
                            (isClickable ? '<p class="text-xs text-blue-600 mt-1 hover:text-blue-800"><i class="fas fa-external-link-alt mr-1"></i>Click to view</p>' : '') +
                        '</div>' +
                        '<div class="ml-3 flex-shrink-0">' +
                            (!isRead ? 
                                '<button type="button" class="p-1 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-100 transition-colors duration-150 notif-mark" data-id="' + notifId + '" title="Mark as read">' +
                                    '<i class="fas fa-check text-xs"></i>' +
                                '</button>' :
                                '<button type="button" class="p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors duration-150" title="Already read">' +
                                    '<i class="fas fa-check-double text-xs"></i>' +
                                '</button>'
                            ) +
                        '</div>' +
                    '</div>';
                }
            } else {
                html = '<div class="p-8 text-center text-gray-500">' +
                    '<div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">' +
                        '<i class="far fa-bell-slash text-2xl text-gray-400"></i>' +
                    '</div>' +
                    '<h3 class="text-sm font-medium text-gray-900 mb-1">No notifications</h3>' +
                    '<p class="text-xs text-gray-500">You\'re all caught up! Check back later for new updates.</p>' +
                '</div>';
            }
            notifList.html(html);
        }, 'json');
    }

    // Notification bell click
    $('#notifBell').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#notifDropdown').toggleClass('hidden');
        fetchNotifications();
        $('#notifTime').text('Just now');
    });

    // Handle clickable notifications (view course/materials)
    notifList.on('click', '.notif-card', function(e) {
        var card = $(this);
        var notifId = card.data('notif-id');
        var action = card.data('action');

        if (!notifId || !action) {
            return;
        }

        card.addClass('opacity-70');

        $.get('<?= base_url('notifications/resolve') ?>/' + notifId, function(resp) {
            if (resp && resp.success && resp.url) {
                window.location.href = resp.url;
            } else {
                card.removeClass('opacity-70');
                alert((resp && resp.message) ? resp.message : 'Unable to open notification.');
            }
        }, 'json').fail(function() {
            card.removeClass('opacity-70');
            alert('Unable to open notification right now.');
        });
    });

    notifList.on('click', '.notif-mark', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Mark all notifications as read
    $('#markAllRead').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var btn = $(this);
        var originalText = btn.text();
        btn.text('Marking...').prop('disabled', true);
        
        var csrfInput = $('input[name^="<?= csrf_token() ?>"]').first();
        var csrfName = csrfInput.attr('name');
        var csrfHash = csrfInput.val();
        
        var data = {};
        if (csrfName && csrfHash) {
            data[csrfName] = csrfHash;
        }
        
        $.ajax({
            url: '<?= base_url('notifications/mark_read') ?>',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('.notif-mark').each(function() {
                        $(this).replaceWith('<button type="button" class="p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition-colors duration-150" title="Already read"><i class="fas fa-check-double text-xs"></i></button>');
                    });
                    
                    $('.bg-blue-50').removeClass('bg-blue-50 border-l-4 border-l-blue-500');
                    $('.font-semibold').removeClass('font-semibold');
                    notifBadge.hide();
                    
                    var feedback = $('<span class="text-xs text-green-600 ml-2">All marked read!</span>');
                    btn.after(feedback);
                    setTimeout(function() {
                        feedback.fadeOut(function() {
                            feedback.remove();
                            btn.text(originalText).prop('disabled', false);
                        });
                    }, 2000);
                    
                    setTimeout(function() {
                        fetchNotifications();
                    }, 2500);
                } else {
                    btn.text(originalText).prop('disabled', false);
                    var error = $('<span class="text-xs text-red-600 ml-2">' + (resp.message || 'Failed') + '</span>');
                    btn.after(error);
                    setTimeout(function() { error.fadeOut(function() { error.remove(); }); }, 2000);
                }
                
                if (resp.csrf_hash) {
                    csrfInput.val(resp.csrf_hash);
                }
            },
            error: function(xhr, status, error) {
                console.error('Mark all read error:', xhr.responseText);
                btn.text(originalText).prop('disabled', false);
                var error = $('<span class="text-xs text-red-600 ml-2">Error</span>');
                btn.after(error);
                setTimeout(function() { error.fadeOut(function() { error.remove(); }); }, 2000);
            }
        });
    });

    // Fetch notifications on page load
    fetchNotifications();
    
    // Auto-refresh notifications every 30 seconds
    setInterval(function() {
        fetchNotifications();
    }, 30000);

    // Handle material deletion
    $('.delete-material').on('click', function(e) {
        e.preventDefault();
        var materialId = $(this).data('material-id');
        var materialItem = $(this).closest('li');
        
        if (confirm('Are you sure you want to delete this material?')) {
            $.ajax({
                url: '<?= base_url('materials/delete') ?>/' + materialId,
                type: 'POST',
                data: {
                    '<?= csrf_token() ?>': $('input[name="<?= csrf_token() ?>"]').val()
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    if (response.success) {
                        // Remove the material from the list
                        materialItem.fadeOut(300, function() {
                            $(this).remove();
                        });
                        // Show success message
                        alert(response.message || 'Material deleted successfully!');
                    } else {
                        alert(response.message || 'Failed to delete material');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Delete error:', xhr.responseText);
                    alert('An error occurred while deleting the material');
                }
            });
        }
    });

    // Filter functions for Student Dashboard
    window.filterCourses = function() {
        try {
            const schoolYearEl = document.getElementById('filterSchoolYear');
            const semesterEl = document.getElementById('filterSemester');
            const searchInputEl = document.getElementById('searchCourseInput');
            
            if (!searchInputEl) {
                console.error('Search input element not found');
                return;
            }
            
            const schoolYear = schoolYearEl ? schoolYearEl.value : '';
            const semester = semesterEl ? semesterEl.value : '';
            const searchTerm = searchInputEl.value ? searchInputEl.value.toLowerCase().trim() : '';
            const courses = document.querySelectorAll('.course-item');
            let visibleCount = 0;

            if (courses.length === 0) {
                return;
            }

            courses.forEach(course => {
                const courseSchoolYear = course.getAttribute('data-school-year') || '';
                const courseSemester = course.getAttribute('data-semester') || '';
                const courseCode = course.getAttribute('data-course-code') || '';
                
                // Get course text content for search
                const courseTitleEl = course.querySelector('.course-title');
                const courseTitle = courseTitleEl ? courseTitleEl.textContent.toLowerCase().trim() : '';
                
                // Get description - try multiple selectors
                let courseDescription = '';
                const descElement = course.querySelector('p.text-sm.text-gray-600') || 
                                  course.querySelector('p.mt-2.text-sm.text-gray-600') ||
                                  course.querySelector('.card-text');
                if (descElement) {
                    courseDescription = descElement.textContent.toLowerCase().trim();
                }
                
                // Get all text from the course card (includes course code, academic info, etc.)
                // Remove extra whitespace and normalize
                const fullText = course.textContent.toLowerCase().replace(/\s+/g, ' ').trim();
                
                // Combine searchable text - include title, description, code
                const searchableText = (courseTitle + ' ' + courseDescription + ' ' + courseCode).toLowerCase().replace(/\s+/g, ' ').trim();
                
                const matchSchoolYear = !schoolYear || courseSchoolYear === schoolYear;
                const matchSemester = !semester || courseSemester === semester;
                
                // Search in both specific fields and full text
                const matchSearch = !searchTerm || 
                                  searchableText.includes(searchTerm) || 
                                  fullText.includes(searchTerm) ||
                                  courseTitle.includes(searchTerm) ||
                                  courseCode.toLowerCase().includes(searchTerm);
                
                if (matchSchoolYear && matchSemester && matchSearch) {
                    // Show course
                    course.style.display = '';
                    course.style.visibility = 'visible';
                    course.style.opacity = '1';
                    course.classList.remove('hidden');
                    visibleCount++;
                } else {
                    // Hide course
                    course.style.display = 'none';
                    course.style.visibility = 'hidden';
                    course.style.opacity = '0';
                    course.classList.add('hidden');
                }
            });

            // Update count
            const countElement = document.getElementById('enrolledCount');
            if (countElement) {
                countElement.textContent = visibleCount;
            }
        } catch (error) {
            console.error('Error in filterCourses:', error);
        }
    }

    window.clearFilters = function() {
        const filterSchoolYear = document.getElementById('filterSchoolYear');
        const filterSemester = document.getElementById('filterSemester');
        const searchInput = document.getElementById('searchCourseInput');
        
        if (filterSchoolYear) filterSchoolYear.value = '';
        if (filterSemester) filterSemester.value = '';
        if (searchInput) searchInput.value = '';
        
        if (typeof filterCourses === 'function') {
            filterCourses();
        }
    }

    // Filter functions for Teacher Dashboard
    function filterCoursesTeacher() {
        const schoolYear = document.getElementById('filterSchoolYearTeacher')?.value || '';
        const semester = document.getElementById('filterSemesterTeacher')?.value || '';
        const courses = document.querySelectorAll('.course-item-teacher');

        courses.forEach(course => {
            const courseSchoolYear = course.getAttribute('data-school-year') || '';
            const courseSemester = course.getAttribute('data-semester') || '';
            
            const matchSchoolYear = !schoolYear || courseSchoolYear === schoolYear;
            const matchSemester = !semester || courseSemester === semester;
            
            if (matchSchoolYear && matchSemester) {
                course.style.display = '';
            } else {
                course.style.display = 'none';
            }
        });
    }

    function clearFiltersTeacher() {
        document.getElementById('filterSchoolYearTeacher').value = '';
        document.getElementById('filterSemesterTeacher').value = '';
        filterCoursesTeacher();
    }

    // Add event listeners for filters
    function initializeFilters() {
        const filterSchoolYear = document.getElementById('filterSchoolYear');
        const filterSemester = document.getElementById('filterSemester');
        const searchCourseInput = document.getElementById('searchCourseInput');
        const filterSchoolYearTeacher = document.getElementById('filterSchoolYearTeacher');
        const filterSemesterTeacher = document.getElementById('filterSemesterTeacher');

        console.log('Initializing filters...', {
            filterSchoolYear: !!filterSchoolYear,
            filterSemester: !!filterSemester,
            searchCourseInput: !!searchCourseInput
        });

        if (filterSchoolYear) {
            filterSchoolYear.addEventListener('change', function() {
                console.log('School year changed');
                if (typeof filterCourses === 'function') filterCourses();
            });
        }
        if (filterSemester) {
            filterSemester.addEventListener('change', function() {
                console.log('Semester changed');
                if (typeof filterCourses === 'function') filterCourses();
            });
        }
        if (searchCourseInput) {
            searchCourseInput.addEventListener('input', function(e) {
                console.log('Search input:', e.target.value);
                if (typeof filterCourses === 'function') filterCourses();
            });
            searchCourseInput.addEventListener('keyup', function(e) {
                console.log('Search keyup:', e.target.value);
                if (typeof filterCourses === 'function') filterCourses();
            });
            searchCourseInput.addEventListener('paste', function() {
                setTimeout(function() {
                    if (typeof filterCourses === 'function') filterCourses();
                }, 10);
            });
        }
        if (filterSchoolYearTeacher) {
            filterSchoolYearTeacher.addEventListener('change', filterCoursesTeacher);
        }
        if (filterSemesterTeacher) {
            filterSemesterTeacher.addEventListener('change', filterCoursesTeacher);
        }
    }

    // Initialize filters - use jQuery ready since we're in jQuery context
    $(document).ready(function() {
        // Wait a bit to ensure all elements are rendered
        setTimeout(function() {
            initializeFilters();
        }, 100);
    });

});
    </script>
    
    <!-- Separate script for search functionality to ensure it works -->
    <script>
    // Ensure search works even if jQuery ready hasn't fired yet
    (function() {
        function setupSearch() {
            const searchInput = document.getElementById('searchCourseInput');
            if (searchInput && !searchInput.dataset.listenerAttached) {
                searchInput.dataset.listenerAttached = 'true';
                
                // Remove any existing listeners by cloning
                const newInput = searchInput.cloneNode(true);
                searchInput.parentNode.replaceChild(newInput, searchInput);
                
                // Add fresh event listeners
                newInput.addEventListener('input', function(e) {
                    // Don't prevent default - allow normal input
                    if (typeof window.filterCourses === 'function') {
                        setTimeout(function() {
                            window.filterCourses();
                        }, 50);
                    }
                });
                
                newInput.addEventListener('keyup', function(e) {
                    // Don't prevent default - allow normal typing
                    if (typeof window.filterCourses === 'function') {
                        window.filterCourses();
                    }
                });
                
                console.log('Search input listeners attached');
            }
        }
        
        // Try multiple times to ensure it works
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setupSearch();
                setTimeout(setupSearch, 500);
            });
        } else {
            setupSearch();
            setTimeout(setupSearch, 500);
        }
        
        // Also try when window loads
        window.addEventListener('load', function() {
            setTimeout(setupSearch, 200);
        });
    })();
    </script>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeAddStudentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">
                                Add Student to Course
                            </h3>
                            <p class="text-sm text-gray-500 mb-4" id="courseTitleText"></p>
                            
                            <!-- Search Bar -->
                            <div id="searchBarContainer" class="mb-4 hidden">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           id="studentSearchInput" 
                                           onkeyup="filterStudents()"
                                           oninput="filterStudents()"
                                           placeholder="Search" 
                                           minlength="0"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm">
                                </div>
                            </div>
                            
                            <!-- Loading State -->
                            <div id="studentLoading" class="text-center py-4">
                                <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                <p class="text-sm text-gray-500 mt-2">Loading students...</p>
                            </div>

                            <!-- Student List -->
                            <div id="studentList" class="max-h-96 overflow-y-auto hidden">
                                <div class="space-y-2" id="studentListContainer">
                                    <!-- Students will be populated here -->
                                </div>
                            </div>

                            <!-- No Students Available -->
                            <div id="noStudents" class="text-center py-4 hidden">
                                <i class="fas fa-user-slash text-gray-300 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-500">All students are already enrolled in this course.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeAddStudentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentCourseId = null;
        let allStudents = []; // Store all students for filtering

        function openAddStudentModal(courseId, courseTitle) {
            currentCourseId = courseId;
            document.getElementById('courseTitleText').textContent = 'Course: ' + courseTitle;
            document.getElementById('addStudentModal').classList.remove('hidden');
            
            // Clear search input
            document.getElementById('studentSearchInput').value = '';
            
            // Show loading, hide others
            document.getElementById('studentLoading').classList.remove('hidden');
            document.getElementById('studentList').classList.add('hidden');
            document.getElementById('noStudents').classList.add('hidden');
            document.getElementById('searchBarContainer').classList.add('hidden');

            // Fetch available students
            fetch('<?= base_url('course/') ?>' + courseId + '/students/available')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('studentLoading').classList.add('hidden');
                    
                    if (data.success && data.students.length > 0) {
                        allStudents = data.students; // Store students for filtering
                        renderStudentList(allStudents);
                        document.getElementById('studentList').classList.remove('hidden');
                        document.getElementById('searchBarContainer').classList.remove('hidden');
                    } else {
                        allStudents = [];
                        document.getElementById('noStudents').classList.remove('hidden');
                        document.getElementById('searchBarContainer').classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('studentLoading').classList.add('hidden');
                    alert('Failed to load students. Please try again.');
                });
        }

        function renderStudentList(students) {
            const studentListContainer = document.getElementById('studentListContainer');
            studentListContainer.innerHTML = '';
            
            if (students.length === 0) {
                studentListContainer.innerHTML = `
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">No students found matching your search.</p>
                    </div>
                `;
                return;
            }
            
            students.forEach(student => {
                const studentItem = document.createElement('div');
                studentItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100 student-item';
                studentItem.setAttribute('data-name', student.name.toLowerCase());
                studentItem.setAttribute('data-email', student.email.toLowerCase());
                studentItem.innerHTML = `
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">${student.name}</p>
                        <p class="text-xs text-gray-500">${student.email}</p>
                    </div>
                    <button onclick="addStudentToCourse(${student.id})" 
                            class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                        <i class="fas fa-plus mr-1"></i> Add
                    </button>
                `;
                studentListContainer.appendChild(studentItem);
            });
        }

        function filterStudents() {
            const searchInput = document.getElementById('studentSearchInput');
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            if (!searchTerm) {
                // Show all students if search is empty
                renderStudentList(allStudents);
                return;
            }
            
            // Filter students by name or email (works with 2+ characters)
            const filteredStudents = allStudents.filter(student => {
                const name = student.name.toLowerCase();
                const email = student.email.toLowerCase();
                
                // Check if search term matches name or email (works with any length >= 2)
                const nameMatch = name.includes(searchTerm);
                const emailMatch = email.includes(searchTerm);
                
                return nameMatch || emailMatch;
            });
            
            renderStudentList(filteredStudents);
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
            document.getElementById('studentSearchInput').value = '';
            currentCourseId = null;
            allStudents = [];
        }

        function addStudentToCourse(studentId) {
            if (!currentCourseId) return;

            const formData = new FormData();
            formData.append('course_id', currentCourseId);
            formData.append('student_id', studentId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('course/add-student') ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment request sent to student!');
                        // Reload the page to update student count
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to add student');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        function acceptEnrollment(enrollmentId) {
            if (!confirm('Are you sure you want to accept this enrollment request?')) {
                return;
            }

            const formData = new FormData();
            formData.append('enrollment_id', enrollmentId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('course/accept-enrollment') ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment accepted successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to accept enrollment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        function rejectEnrollment(enrollmentId) {
            if (!confirm('Are you sure you want to reject this enrollment request?')) {
                return;
            }

            const formData = new FormData();
            formData.append('enrollment_id', enrollmentId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('course/reject-enrollment') ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment request rejected.');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to reject enrollment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        // Teacher approval functions
        function teacherApproveEnrollment(enrollmentId, courseId) {
            if (!confirm('Are you sure you want to approve this enrollment request?')) {
                return;
            }

            const formData = new FormData();
            formData.append('enrollment_id', enrollmentId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('course/teacher-approve-enrollment') ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment approved successfully!');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to approve enrollment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        function teacherRejectEnrollment(enrollmentId, courseId) {
            if (!confirm('Are you sure you want to reject this enrollment request?')) {
                return;
            }

            const formData = new FormData();
            formData.append('enrollment_id', enrollmentId);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url('course/teacher-reject-enrollment') ?>', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Enrollment request rejected.');
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to reject enrollment');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
        }

        // Enrollment Details Modal Functions
        let currentEnrollmentCourseId = null;
        
        function openEnrollmentDetailsModal(courseId, courseTitle) {
            currentEnrollmentCourseId = courseId;
            document.getElementById('enrollmentDetailsModal').classList.remove('hidden');
            document.getElementById('enrollmentCourseTitle').textContent = courseTitle;
            
            // Show loading
            document.getElementById('enrollmentLoading').classList.remove('hidden');
            document.getElementById('enrollmentContent').classList.add('hidden');
            document.getElementById('enrollmentError').classList.add('hidden');
            
            // Fetch enrollment details
            fetch('<?= base_url('course/') ?>' + courseId + '/enrollments')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('enrollmentLoading').classList.add('hidden');
                    
                    if (data.success) {
                        renderEnrollmentDetails(data);
                        document.getElementById('enrollmentContent').classList.remove('hidden');
                    } else {
                        document.getElementById('enrollmentError').classList.remove('hidden');
                        document.getElementById('enrollmentError').textContent = data.message || 'Failed to load enrollment details';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('enrollmentLoading').classList.add('hidden');
                    document.getElementById('enrollmentError').classList.remove('hidden');
                    document.getElementById('enrollmentError').textContent = 'Failed to load enrollment details. Please try again.';
                });
        }

        function renderEnrollmentDetails(data) {
            const summary = data.summary;
            const enrollments = data.enrollments;
            
            // Update summary
            document.getElementById('enrollmentSummary').innerHTML = `
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center p-3 bg-green-50 rounded">
                        <div class="text-2xl font-bold text-green-600">${summary.accepted}</div>
                        <div class="text-xs text-gray-600">Accepted</div>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded">
                        <div class="text-2xl font-bold text-yellow-600">${summary.pending}</div>
                        <div class="text-xs text-gray-600">Pending</div>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded">
                        <div class="text-2xl font-bold text-red-600">${summary.rejected}</div>
                        <div class="text-xs text-gray-600">Rejected</div>
                    </div>
                </div>
            `;
            
            // Render accepted students
            const acceptedContainer = document.getElementById('acceptedStudentsList');
            if (enrollments.accepted.length > 0) {
                acceptedContainer.innerHTML = enrollments.accepted.map(enrollment => `
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-md mb-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${escapeHtml(enrollment.student_name || 'Unknown')}</p>
                            <p class="text-xs text-gray-500">${escapeHtml(enrollment.student_email || '')}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                ${new Date(enrollment.enrollment_date).toLocaleString()}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-200 rounded">
                                <i class="fas fa-check-circle mr-1"></i>Accepted
                            </span>
                            <button type="button" 
                                    onclick="confirmRemoveStudentFromCourse(${enrollment.user_id}, ${currentEnrollmentCourseId}, '${escapeHtml(enrollment.student_name || 'Student')}', '${escapeHtml(document.getElementById('enrollmentCourseTitle').textContent)}')"
                                    class="px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    title="Remove student">
                                <i class="fas fa-user-minus mr-1"></i> Remove
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                acceptedContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No accepted enrollments yet.</p>';
            }
            
            // Render pending students
            const pendingContainer = document.getElementById('pendingStudentsList');
            if (enrollments.pending.length > 0) {
                pendingContainer.innerHTML = enrollments.pending.map(enrollment => `
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-md mb-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${enrollment.student_name}</p>
                            <p class="text-xs text-gray-500">${enrollment.student_email}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                ${new Date(enrollment.enrollment_date).toLocaleString()}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-200 rounded">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </span>
                    </div>
                `).join('');
            } else {
                pendingContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No pending enrollments.</p>';
            }
            
            // Render rejected students
            const rejectedContainer = document.getElementById('rejectedStudentsList');
            if (enrollments.rejected.length > 0) {
                rejectedContainer.innerHTML = enrollments.rejected.map(enrollment => `
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-md mb-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">${enrollment.student_name}</p>
                            <p class="text-xs text-gray-500">${enrollment.student_email}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                ${new Date(enrollment.enrollment_date).toLocaleString()}
                            </p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-200 rounded">
                            <i class="fas fa-times-circle mr-1"></i>Rejected
                        </span>
                    </div>
                `).join('');
            } else {
                rejectedContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">No rejected enrollments.</p>';
            }
        }

        function closeEnrollmentDetailsModal() {
            document.getElementById('enrollmentDetailsModal').classList.add('hidden');
            document.getElementById('enrollmentContent').classList.add('hidden');
            document.getElementById('enrollmentError').classList.add('hidden');
        }

        // Modal backdrop already handles closing via onclick attribute
    </script>

    <!-- Enrollment Details Modal -->
    <div id="enrollmentDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="enrollment-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeEnrollmentDetailsModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="enrollment-modal-title">
                                Enrollment Details
                            </h3>
                            <p class="text-sm text-gray-500 mb-4" id="enrollmentCourseTitle"></p>
                            
                            <!-- Loading State -->
                            <div id="enrollmentLoading" class="text-center py-8">
                                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                                <p class="text-sm text-gray-500 mt-2">Loading enrollment details...</p>
                            </div>
                            
                            <!-- Error State -->
                            <div id="enrollmentError" class="hidden text-center py-4 text-red-600"></div>
                            
                            <!-- Content -->
                            <div id="enrollmentContent" class="hidden">
                                <!-- Summary -->
                                <div id="enrollmentSummary" class="mb-6"></div>
                                
                                <!-- Tabs -->
                                <div class="border-b border-gray-200 mb-4">
                                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                        <button onclick="showEnrollmentTab('accepted')" 
                                                class="enrollment-tab active border-b-2 border-green-500 py-2 px-1 text-sm font-medium text-green-600" 
                                                data-tab="accepted">
                                            Accepted
                                        </button>
                                        <button onclick="showEnrollmentTab('pending')" 
                                                class="enrollment-tab border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-yellow-600 hover:border-yellow-300" 
                                                data-tab="pending">
                                            Pending
                                        </button>
                                        <button onclick="showEnrollmentTab('rejected')" 
                                                class="enrollment-tab border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:text-red-600 hover:border-red-300" 
                                                data-tab="rejected">
                                            Rejected
                                        </button>
                                    </nav>
                                </div>
                                
                                <!-- Tab Content -->
                                <div class="max-h-96 overflow-y-auto">
                                    <div id="acceptedTab" class="enrollment-tab-content">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Accepted Students</h4>
                                        <div id="acceptedStudentsList"></div>
                                    </div>
                                    <div id="pendingTab" class="enrollment-tab-content hidden">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Pending Requests</h4>
                                        <div id="pendingStudentsList"></div>
                                    </div>
                                    <div id="rejectedTab" class="enrollment-tab-content hidden">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Rejected Requests</h4>
                                        <div id="rejectedStudentsList"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeEnrollmentDetailsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle term module (PRELIM, MIDTERM, FINAL)
        function toggleTermModule(termId) {
            const content = document.getElementById('content-' + termId);
            const arrow = document.getElementById('arrow-' + termId);
            
            if (content) {
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    if (arrow) {
                        arrow.classList.remove('fa-chevron-down');
                        arrow.classList.add('fa-chevron-up');
                    }
                } else {
                    content.classList.add('hidden');
                    if (arrow) {
                        arrow.classList.remove('fa-chevron-up');
                        arrow.classList.add('fa-chevron-down');
                    }
                }
            }
        }
        
        // Tab switching for enrollment details
        function showEnrollmentTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.enrollment-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.enrollment-tab').forEach(tab => {
                tab.classList.remove('active', 'border-green-500', 'text-green-600', 'border-yellow-500', 'text-yellow-600', 'border-red-500', 'text-red-600');
                tab.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName + 'Tab').classList.remove('hidden');
            
            // Add active class to selected tab with appropriate color
            const activeTab = document.querySelector(`.enrollment-tab[data-tab="${tabName}"]`);
            if (activeTab) {
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                if (tabName === 'accepted') {
                    activeTab.classList.add('active', 'border-green-500', 'text-green-600');
                } else if (tabName === 'pending') {
                    activeTab.classList.add('active', 'border-yellow-500', 'text-yellow-600');
                } else if (tabName === 'rejected') {
                    activeTab.classList.add('active', 'border-red-500', 'text-red-600');
                }
            }
        }

        // Enrolled Students Modal Functions (for students)
        function openEnrolledStudentsModal(courseId, courseTitle) {
            document.getElementById('enrolledStudentsModal').classList.remove('hidden');
            document.getElementById('enrolledStudentsCourseTitle').textContent = courseTitle;
            
            // Show loading
            document.getElementById('enrolledStudentsLoading').classList.remove('hidden');
            document.getElementById('enrolledStudentsContent').classList.add('hidden');
            document.getElementById('enrolledStudentsError').classList.add('hidden');
            
            // Fetch enrolled students
            fetch('<?= base_url('course/') ?>' + courseId + '/students')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('enrolledStudentsLoading').classList.add('hidden');
                    
                    if (data.success) {
                        renderEnrolledStudents(data);
                        document.getElementById('enrolledStudentsContent').classList.remove('hidden');
                    } else {
                        document.getElementById('enrolledStudentsError').classList.remove('hidden');
                        document.getElementById('enrolledStudentsError').textContent = data.message || 'Failed to load enrolled students';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('enrolledStudentsLoading').classList.add('hidden');
                    document.getElementById('enrolledStudentsError').classList.remove('hidden');
                    document.getElementById('enrolledStudentsError').textContent = 'Failed to load enrolled students. Please try again.';
                });
        }

        function renderEnrolledStudents(data) {
            const students = data.students;
            const total = data.total;
            
            // Update summary
            document.getElementById('enrolledStudentsSummary').innerHTML = `
                <div class="text-center p-4 bg-blue-50 rounded-lg mb-4">
                    <div class="text-2xl font-bold text-blue-600">${total}</div>
                    <div class="text-sm text-gray-600">Total Enrolled Students</div>
                </div>
            `;
            
            // Render students list
            const studentsContainer = document.getElementById('enrolledStudentsList');
            if (students.length > 0) {
                studentsContainer.innerHTML = students.map((student, index) => `
                    <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm mb-2 border border-gray-200">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold">${index + 1}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">${student.student_name}</p>
                                <p class="text-xs text-gray-500">${student.student_email}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="far fa-calendar mr-1"></i>
                                    Enrolled: ${new Date(student.enrollment_date).toLocaleDateString()}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Enrolled
                        </span>
                    </div>
                `).join('');
            } else {
                studentsContainer.innerHTML = '<p class="text-sm text-gray-500 text-center py-8">No enrolled students found.</p>';
            }
        }

        function closeEnrolledStudentsModal() {
            document.getElementById('enrolledStudentsModal').classList.add('hidden');
            document.getElementById('enrolledStudentsContent').classList.add('hidden');
            document.getElementById('enrolledStudentsError').classList.add('hidden');
        }
    </script>

    <!-- Enrolled Students Modal (for students) -->
    <div id="enrolledStudentsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="enrolled-students-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeEnrolledStudentsModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="enrolled-students-modal-title">
                                Enrolled Students
                            </h3>
                            <p class="text-sm text-gray-500 mb-4" id="enrolledStudentsCourseTitle"></p>
                            
                            <!-- Loading State -->
                            <div id="enrolledStudentsLoading" class="text-center py-8">
                                <i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>
                                <p class="text-sm text-gray-500 mt-2">Loading enrolled students...</p>
                            </div>
                            
                            <!-- Error State -->
                            <div id="enrolledStudentsError" class="hidden text-center py-4 text-red-600"></div>
                            
                            <!-- Content -->
                            <div id="enrolledStudentsContent" class="hidden">
                                <!-- Summary -->
                                <div id="enrolledStudentsSummary" class="mb-4"></div>
                                
                                <!-- Students List -->
                                <div class="max-h-96 overflow-y-auto">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Classmates</h4>
                                    <div id="enrolledStudentsList"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeEnrolledStudentsModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
