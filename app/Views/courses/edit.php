<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Learning Management System</title>
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
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
            width: 100%;
            position: fixed;
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
    </style>
</head>
<body>
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
                        <?php elseif(session('role') == 'teacher'): ?>
                            <div class="mt-4 mb-2">
                                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Teaching</p>
                            </div>
                            
                            <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'my-courses' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'my-courses' ? '' : 'text-gray-500' ?> fas fa-book-reader"></i>
                                My Courses
                            </a>
                            
                            <a href="<?= base_url('create-course') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium <?= $currentPage == 'create-course' ? 'text-white bg-primary' : 'text-gray-700 hover:bg-gray-100' ?> rounded-lg">
                                <i class="w-5 h-5 mr-3 <?= $currentPage == 'create-course' ? '' : 'text-gray-500' ?> fas fa-plus-circle"></i>
                                Create Course
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
                            <img class="w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode(session('name') ?? 'User') ?>" alt="<?= esc(session('name') ?? 'User') ?>">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700"><?= esc(session('name') ?? 'User') ?></p>
                            <p class="text-xs text-gray-500"><?= ucfirst(esc(session('role') ?? 'user')) ?></p>
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
                    <!-- Page Header -->
                    <div class="page-header" style="background: white; padding: 25px; border: 3px solid #333; border-radius: 3px; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h1 style="font-size: 28px; color: #333; margin-bottom: 8px;">Edit Course</h1>
                                <p style="color: #666; font-size: 14px;">Update course information</p>
                            </div>
                        </div>
                    </div>

                    <!-- Flash Messages -->
                    <?php if (!empty(session()->getFlashdata('success'))): ?>
                        <div class="alert alert-success" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #d4edda; color: #155724; border-color: #c3e6cb;">
                            <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty(session()->getFlashdata('error'))): ?>
                        <div class="alert alert-danger" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Course Form -->
                    <div class="section-card" style="background: white; border: 2px solid #999; border-radius: 3px; padding: 25px; margin-bottom: 25px;">
                        <form action="<?= base_url('edit-course/' . $course['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="title" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Course Title *</label>
                                <input type="text" id="title" name="title" required
                                       value="<?= esc($course['title'] ?? old('title')) ?>"
                                       style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                <?php if (isset($validation) && $validation->getError('title')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('title') ?></p>
                                <?php endif; ?>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="description" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Course Description *</label>
                                <textarea id="description" name="description" rows="6" required
                                          style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"><?= esc($course['description'] ?? old('description')) ?></textarea>
                                <?php if (isset($validation) && $validation->getError('description')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('description') ?></p>
                                <?php endif; ?>
                                <p style="margin-top: 5px; font-size: 12px; color: #666;">Minimum 10 characters required</p>
                            </div>

                            <!-- Teacher Assignment (Admin Only) -->
                            <?php if(session('role') == 'admin' && isset($teachers) && !empty($teachers)): ?>
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="teacher_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Assign Teacher</label>
                                <select id="teacher_id" name="teacher_id"
                                        style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <option value="">Select a teacher</option>
                                    <?php foreach($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>" <?= (isset($course['teacher_id']) && $course['teacher_id'] == $teacher['id']) ? 'selected' : '' ?>>
                                            <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- Program Assignment -->
                            <?php if(isset($programs) && !empty($programs)): ?>
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="program_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Program</label>
                                <select id="program_id" name="program_id"
                                        style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <option value="">Select a program (optional)</option>
                                    <?php foreach($programs as $program): ?>
                                        <option value="<?= $program['id'] ?>" <?= (isset($course['program_id']) && $course['program_id'] == $program['id']) ? 'selected' : '' ?>>
                                            <?= esc($program['code']) ?> - <?= esc($program['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <!-- Submit Buttons -->
                            <div style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                                <a href="<?= session('role') == 'admin' ? base_url('courses') : base_url('my-courses') ?>" class="btn" style="background: #ccc; color: #333; border-color: #999; padding: 8px 15px; border: 2px solid; border-radius: 3px; font-weight: bold; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-block;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn" style="background: #1976d2; color: white; border-color: #1565c0; padding: 8px 15px; border: 2px solid; border-radius: 3px; font-weight: bold; font-size: 13px; cursor: pointer;">
                                    <i class="fas fa-save"></i> Update Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

