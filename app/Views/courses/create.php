<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - Learning Management System</title>
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
                        <a href="<?= base_url('my-courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100">
                            <i class="w-5 h-5 mr-3 text-gray-500 fas fa-book-reader"></i>
                            My Courses
                        </a>
                        <a href="<?= base_url('create-course') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
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
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">Create New Course</h1>
                    </div>
                </div>
            </header>

            <!-- Create Course Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-3xl mx-auto">
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

                    <!-- Create Course Form -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Course Information</h2>
                            <p class="text-gray-600 mt-1">Fill in the details below to create your new course.</p>
                        </div>

                        <form action="<?= base_url('create-course') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="space-y-6">
                                <!-- Course Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Course Title *</label>
                                    <input type="text" id="title" name="title" required
                                           value="<?= old('title') ?>"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                           placeholder="e.g., Introduction to Web Development">
                                    <?php if (isset($validation) && $validation->getError('title')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('title') ?></p>
                                    <?php endif; ?>
                                </div>

                                <!-- Course Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Course Description *</label>
                                    <textarea id="description" name="description" rows="6" required
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                              placeholder="Provide a detailed description of your course..."><?= old('description') ?></textarea>
                                    <?php if (isset($validation) && $validation->getError('description')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('description') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Minimum 10 characters required</p>
                                </div>

                                <!-- Course Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Course Category</label>
                                    <select id="category" name="category" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">Select a category</option>
                                        <option value="programming">Programming</option>
                                        <option value="design">Design</option>
                                        <option value="business">Business</option>
                                        <option value="marketing">Marketing</option>
                                        <option value="data-science">Data Science</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Course Level -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Course Level</label>
                                    <div class="mt-2 space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="level" value="beginner" checked class="text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Beginner</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="level" value="intermediate" class="text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Intermediate</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="level" value="advanced" class="text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-700">Advanced</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Course Duration -->
                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-700">Estimated Duration (weeks)</label>
                                    <input type="number" id="duration" name="duration" min="1" max="52"
                                           value="4"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <p class="mt-1 text-xs text-gray-500">How many weeks will this course take to complete?</p>
                                </div>

                                <!-- Course Visibility -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="published" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <span class="ml-2 text-sm text-gray-700">Publish course immediately</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">Uncheck this if you want to save the course as a draft</p>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="mt-8 flex justify-end space-x-3">
                                <a href="<?= base_url('my-courses') ?>" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                    <i class="fas fa-save mr-2"></i> Create Course
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tips Section -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-blue-900 mb-3">
                            <i class="fas fa-lightbulb mr-2"></i> Tips for Creating a Great Course
                        </h3>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                                <span>Use a clear, descriptive title that tells students what they'll learn</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                                <span>Write a detailed description that highlights the benefits and outcomes</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                                <span>Choose the appropriate difficulty level for your target audience</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mr-2 mt-0.5 text-blue-600"></i>
                                <span>Set realistic time expectations for course completion</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
