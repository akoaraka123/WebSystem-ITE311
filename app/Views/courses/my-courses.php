<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Learning Management System</title>
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
                    backgroundImage: {
                        'primary-gradient': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="flex w-64 flex-col bg-white shadow-lg">
            <div class="flex items-center justify-center h-16 px-4 bg-primary">
                <h1 class="text-xl font-bold text-white">LMS</h1>
            </div>
            <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                <nav class="flex-1 space-y-1">
                    <a href="<?= base_url('dashboard') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
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
                        <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
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
                        <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
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
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">
                            <?= session('role') == 'teacher' ? 'My Courses' : 'My Learning' ?>
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if(session('role') == 'teacher'): ?>
                            <a href="<?= base_url('create-course') ?>" class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-md hover:bg-opacity-90">
                                <i class="fas fa-plus mr-2"></i> Create Course
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- My Courses Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Welcome Section -->
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    <?= session('role') == 'teacher' ? 'Your Courses' : 'Your Learning Journey' ?>
                                </h2>
                                <p class="text-gray-600 mt-1">
                                    <?= session('role') == 'teacher' 
                                        ? 'Manage and monitor all the courses you have created.' 
                                        : 'Track your progress and access your enrolled courses.' ?>
                                </p>
                            </div>
                            <div class="text-center">
                                <div class="text-3xl font-bold text-primary"><?= count($courses) ?></div>
                                <div class="text-sm text-gray-500">
                                    <?= session('role') == 'teacher' ? 'Courses Created' : 'Courses Enrolled' ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses List -->
                    <?php if (!empty($courses)): ?>
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($courses as $course): ?>
                                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h3>
                                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                <?= session('role') == 'teacher' ? 'Teaching' : 'Enrolled' ?>
                                            </span>
                                        </div>
                                        
                                        <?php if (!empty($course['description'])): ?>
                                            <p class="text-gray-600 text-sm mb-4"><?= esc($course['description']) ?></p>
                                        <?php endif; ?>

                                        <div class="flex items-center text-sm text-gray-500 mb-4">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>
                                                <?= session('role') == 'teacher' 
                                                    ? 'Created: ' . date('M j, Y', strtotime($course['created_at'] ?? 'now'))
                                                    : 'Enrolled: ' . (isset($course['enrollment_date']) ? date('M j, Y', strtotime($course['enrollment_date'])) : 'N/A') ?>
                                            </span>
                                        </div>

                                        <?php if(session('role') == 'teacher'): ?>
                                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                                <i class="fas fa-users mr-2"></i>
                                                <span>Students: 0</span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="flex space-x-2">
                                            <?php if(session('role') == 'teacher'): ?>
                                                <a href="<?= base_url('course/' . $course['id']) ?>" class="flex-1 px-3 py-2 bg-primary text-white text-sm font-medium rounded hover:bg-opacity-90 text-center">
                                                    <i class="fas fa-tachometer-alt mr-1"></i> Manage
                                                </a>
                                                <a href="<?= base_url('edit-course/' . $course['id']) ?>" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200 text-center">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= base_url('course/' . $course['id']) ?>" class="w-full px-3 py-2 bg-primary text-white text-sm font-medium rounded hover:bg-opacity-90 text-center">
                                                    <i class="fas fa-play mr-1"></i> Continue Learning
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <i class="fas fa-graduation-cap text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <?= session('role') == 'teacher' ? 'No Courses Yet' : 'No Enrollments Yet' ?>
                            </h3>
                            <p class="text-gray-500 mb-6">
                                <?php if(session('role') == 'teacher'): ?>
                                    You haven't created any courses yet. Start by creating your first course.
                                <?php else: ?>
                                    You haven't enrolled in any courses yet. Browse available courses to get started.
                                <?php endif; ?>
                            </p>
                            <div class="space-x-4">
                                <?php if(session('role') == 'teacher'): ?>
                                    <a href="<?= base_url('create-course') ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-md hover:bg-opacity-90">
                                        <i class="fas fa-plus mr-2"></i> Create Course
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('courses') ?>" class="inline-flex items-center px-4 py-2 bg-primary text-white font-medium rounded-md hover:bg-opacity-90">
                                        <i class="fas fa-search mr-2"></i> Browse Courses
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
