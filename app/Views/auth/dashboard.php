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
        body { font-family: 'Inter', sans-serif; }
        
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --warning-gradient: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }
        
        .sidebar { transition: all 0.3s; }
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
        
        .sidebar-item {
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover {
            background-color: rgba(79, 70, 229, 0.1);
            padding-left: 1.5rem;
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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
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
                        <a href="<?= base_url('dashboard') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
                            <i class="w-5 h-5 mr-3 fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <?php if(session('role') == 'admin'): ?>
                            <a href="<?= base_url('users') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-users"></i>
                                Manage Users
                            </a>
                            <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-book"></i>
                                Manage Courses
                            </a>
                        <?php elseif(session('role') == 'teacher'): ?>
                            <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-book-reader"></i>
                                My Courses
                            </a>
                            <a href="<?= base_url('create-course') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-plus-circle"></i>
                                Create Course
                            </a>
                        <?php elseif(session('role') == 'student'): ?>
                            <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-book-open"></i>
                                Browse Courses
                            </a>
                            <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                                <i class="w-5 h-5 mr-3 text-gray-500 fas fa-graduation-cap"></i>
                                My Learning
                            </a>
                        <?php endif; ?>
                        
                        <hr class="my-4 border-gray-200">
                        
                        <a href="<?= base_url('profile') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="w-5 h-5 mr-3 text-gray-500 fas fa-user"></i>
                            Profile
                        </a>
                        <a href="<?= base_url('settings') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="w-5 h-5 mr-3 text-gray-500 fas fa-cog"></i>
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
        <div class="flex flex-col flex-1 overflow-hidden">
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
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">My Enrolled Courses</h3>
                                <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                    <?= !empty($enrolled) ? count($enrolled) . ' Enrolled' : 'Not Enrolled' ?>
                                </span>
                            </div>

                            <?php if (!empty($enrolled)): ?>
                                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                    <?php foreach ($enrolled as $enrollment): ?>
                                        <div class="overflow-hidden bg-white rounded-lg shadow course-card">
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
                                                    <p class="mt-2 text-sm text-gray-600">
                                                        <?= esc($enrollment['description']) ?>
                                                    </p>
                                                <?php endif; ?>

                                                <div class="mt-4 course-materials">
                                                    <h4 class="mb-2 text-sm font-medium text-gray-700">Course Materials</h4>
                                                    <?php if (!empty($materials[$enrollment['course_id']])): ?>
                                                        <ul class="space-y-2">
                                                            <?php foreach ($materials[$enrollment['course_id']] as $material): ?>
                                                                <li class="flex items-center justify-between p-3 text-sm bg-gray-50 rounded-md material-item">
                                                                    <div class="flex-1">
                                                                        <div class="flex items-center mb-1">
                                                                            <i class="mr-3 text-blue-500 fas fa-file-alt"></i>
                                                                            <span class="font-medium"><?= esc($material['file_name'] ?? 'Untitled File') ?></span>
                                                                        </div>
                                                                        <div class="text-xs text-gray-500 ml-7">
                                                                            <i class="far fa-clock mr-1"></i>
                                                                            <?= date('F j, Y - h:i:s A', strtotime($material['created_at'])) ?>
                                                                        </div>
                                                                    </div>
                                                                    <a href="<?= base_url('materials/download/'.($material['id'] ?? '')) ?>" 
                                                                       class="p-2 text-blue-600 rounded-full hover:bg-blue-50 transition-colors duration-200"
                                                                       title="Download">
                                                                        <i class="w-4 h-4 fas fa-download"></i>
                                                                    </a>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <p class="py-4 text-sm text-center text-gray-500 bg-gray-50 rounded-md">
                                                            <i class="mr-2 far fa-folder-open"></i> No materials available yet
                                                        </p>
                                                    <?php endif; ?>
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
                                                <div>
                                                    <h4 class="font-medium text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h4>
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
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">My Courses</h2>
                    <a href="<?= base_url('create-course') ?>" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="mr-2 fas fa-plus"></i> Create New Course
                    </a>
                </div>

                <?php if (!empty($myCourses)): ?>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($myCourses as $course): ?>
                            <div class="overflow-hidden bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300" id="course-<?= esc($course['id']) ?>">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h3>
                                        <span class="px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                            <?= isset($enrollments) && is_array($enrollments) ? ($enrollments[$course['id']] ?? 0) : 0 ?> Students
                                        </span>
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
                                        <div class="uploadMessage text-xs text-center"></div>
                                        <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="mr-2 fas fa-upload"></i> Upload
                                        </button>
                                    </form>

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
                        <p class="mt-1 text-gray-500">Get started by creating your first course.</p>
                        <div class="mt-6">
                            <a href="<?= base_url('create-course') ?>" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="mr-2 fas fa-plus"></i> Create Course
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ADMIN DASHBOARD - Recent Uploads -->
        <?php if ($user['role'] === 'admin'): ?>
            <div class="mt-8">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Recent File Uploads</h3>
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
                            (isClickable ? ' cursor-pointer' : '') + 
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

});
</script>

</body>
</html>
