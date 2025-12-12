<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Learning Management System</title>
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
    <div class="flex h-screen overflow-hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%;">
        <!-- Sidebar -->
        <div id="sidebar-container" class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-full h-full bg-white border-r border-gray-200" style="overflow-y: auto;">
                <div class="flex items-center justify-center h-16 px-4 lms-header shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl mr-3 text-white"></i>
                        <div>
                            <h1 class="text-xl font-bold text-white">ANA ANA LANGS</h1>
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

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden" style="margin-left: 256px; width: calc(100% - 256px);">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">Settings</h1>
                    </div>
                </div>
            </header>

            <!-- Settings Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-4xl mx-auto">
                    <!-- Flash Messages -->
                    <?php if (!empty(session()->getFlashdata('success'))): ?>
                        <div class="p-4 mb-6 text-green-700 bg-green-100 border-l-4 border-green-500 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty(session()->getFlashdata('error'))): ?>
                        <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Settings Sections -->
                    <div class="space-y-6">
                        <!-- Account Settings -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">Manage your account preferences and security settings.</p>
                            
                            <form action="<?= base_url('settings') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="space-y-4">
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="email_notifications" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Email Notifications</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">Receive email updates about your courses and activities</p>
                                    </div>
                                    
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="push_notifications" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Push Notifications</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">Receive browser notifications for important updates</p>
                                    </div>
                                    
                                    <div>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="profile_visibility" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Profile Visibility</span>
                                        </label>
                                        <p class="mt-1 text-xs text-gray-500">Make your profile visible to other users</p>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-primary text-white font-medium rounded-md hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                        <i class="fas fa-save mr-2"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">Control your privacy and data sharing preferences.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_email" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Show Email Address</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">Display your email address on your public profile</p>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="show_last_login" class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Show Last Login Time</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">Display when you were last active</p>
                                </div>
                            </div>
                        </div>

                        <!-- Security Settings -->
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Security Settings</h3>
                            <p class="text-sm text-gray-600 mb-4">Manage your account security and authentication.</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Change Password</h4>
                                        <p class="text-xs text-gray-500">Update your account password</p>
                                    </div>
                                    <a href="<?= base_url('profile') ?>" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        Change
                                    </a>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Two-Factor Authentication</h4>
                                        <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                                    </div>
                                    <button class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        Enable
                                    </button>
                                </div>
                                
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Active Sessions</h4>
                                        <p class="text-xs text-gray-500">Manage your active login sessions</p>
                                    </div>
                                    <button class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
