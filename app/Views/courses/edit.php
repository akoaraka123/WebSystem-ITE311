<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <title>Edit Course - Learning Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
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

                    <?php 
                    $hasValidationErrors = isset($validation) && $validation->hasErrors();
                    $flashError = session()->getFlashdata('error');
                    if ($flashError && !$hasValidationErrors): ?>
                        <div class="alert alert-danger" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                            <?= $flashError ?>
                        </div>
                    <?php elseif ($hasValidationErrors): ?>
                        <div class="alert alert-danger" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                            <div>
                                <strong>Please correct the errors below:</strong>
                                <ul style="margin: 8px 0 0 20px; padding: 0;">
                                    <?php foreach ($validation->getErrors() as $field => $error): ?>
                                        <li style="margin: 4px 0;">
                                            <strong><?= ucfirst(str_replace('_', ' ', $field)) ?>:</strong> <?= esc($error) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
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
                                       style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"
                                       onkeypress="return validateAlphanumericSpace(event)"
                                       oninput="validateInput(this)">
                                <?php if (isset($validation) && $validation->getError('title')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('title') ?></p>
                                <?php endif; ?>
                                <p style="margin-top: 5px; font-size: 12px; color: #666;">Only letters, numbers, and spaces are allowed. Special characters are not permitted.</p>
                                <p id="title_error" style="margin-top: 5px; font-size: 12px; color: #d32f2f; display: none;"></p>
                            </div>

                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="description" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Course Description *</label>
                                <textarea id="description" name="description" rows="6" required
                                          style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"
                                          onkeypress="return validateAlphanumericSpace(event)"
                                          oninput="validateInput(this)"><?= esc($course['description'] ?? old('description')) ?></textarea>
                                <?php if (isset($validation) && $validation->getError('description')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('description') ?></p>
                                <?php endif; ?>
                                <p style="margin-top: 5px; font-size: 12px; color: #666;">Minimum 10 characters required. Only letters, numbers, and spaces are allowed.</p>
                                <p id="description_error" style="margin-top: 5px; font-size: 12px; color: #d32f2f; display: none;"></p>
                            </div>

                            <!-- Teacher Assignment (Admin Only) -->
                            <?php if(session('role') == 'admin' && isset($teachers) && !empty($teachers)): ?>
                            <div class="form-group" style="margin-bottom: 18px; position: relative;">
                                <label for="teacher_search" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Assign Teacher</label>
                                <input type="text" id="teacher_search" placeholder="Search teacher by name or email..." 
                                       style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"
                                       autocomplete="off">
                                <input type="hidden" id="teacher_id" name="teacher_id" value="<?= isset($course['teacher_id']) ? $course['teacher_id'] : '' ?>">
                                <div id="teacher_results" style="display: none; position: absolute; z-index: 1000; width: 100%; margin-top: 4px; background: white; border: 2px solid #999; border-radius: 3px; max-height: 240px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <!-- Results will be populated here -->
                                </div>
                                <div id="selected_teacher" style="margin-top: 8px; padding: 8px; background: #f0f0f0; border-radius: 3px; font-size: 13px; color: #333; <?= (isset($course['teacher_id']) && $course['teacher_id']) ? '' : 'display: none;' ?>">
                                    <span style="font-weight: 600;">Selected: </span><span id="selected_teacher_name"></span>
                                    <button type="button" id="clear_teacher" style="margin-left: 8px; color: #dc2626; cursor: pointer; font-size: 11px; text-decoration: underline;">Clear</button>
                                </div>
                                <p style="margin-top: 6px; font-size: 12px; color: #666;">Type to search for a teacher</p>
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

                            <!-- Academic Year -->
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="acad_year_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Academic Year (Taon ng Akademiko) *</label>
                                <select id="acad_year_id" name="acad_year_id" required
                                        style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <option value="">Select Academic Year</option>
                                    <?php if(isset($academicYears) && !empty($academicYears)): ?>
                                        <?php foreach($academicYears as $acadYear): ?>
                                            <option value="<?= $acadYear['id'] ?>" <?= (isset($course['acad_year_id']) && $course['acad_year_id'] == $acadYear['id']) ? 'selected' : '' ?>>
                                                <?= esc($acadYear['display_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation) && $validation->getError('acad_year_id')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('acad_year_id') ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Semester -->
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="semester_id" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Semester (Semestre) *</label>
                                <select id="semester_id" name="semester_id" required
                                        style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <option value="">Select Semester</option>
                                    <?php if(isset($semesters) && !empty($semesters)): ?>
                                        <?php foreach($semesters as $semester): ?>
                                            <option value="<?= $semester['id'] ?>" <?= (isset($course['semester_id']) && $course['semester_id'] == $semester['id']) ? 'selected' : '' ?>>
                                                <?= esc($semester['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <?php if (isset($validation) && $validation->getError('semester_id')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('semester_id') ?></p>
                                <?php endif; ?>
                            </div>

                            <!-- Course Number (CN) -->
                            <div class="form-group" style="margin-bottom: 18px;">
                                <label for="course_number" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Course Number / Section Code (CN) *</label>
                                <input type="text" id="course_number" name="course_number" required
                                       value="<?= esc($course['course_number'] ?? old('course_number')) ?>"
                                       placeholder="e.g., IT101 A"
                                       style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;"
                                       onkeypress="return validateAlphanumericSpace(event)"
                                       oninput="validateInput(this)">
                                <p style="margin-top: 5px; font-size: 12px; color: #666;">Only letters, numbers, and spaces are allowed.</p>
                                <p id="course_number_error" style="margin-top: 5px; font-size: 12px; color: #d32f2f; display: none;"></p>
                                <?php if (isset($validation) && $validation->getError('course_number')): ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('course_number') ?></p>
                                <?php endif; ?>
                                <p style="margin-top: 5px; font-size: 12px; color: #666;">Unique code for subject or section (e.g., IT101-A)</p>
                            </div>

                            <!-- Schedule -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 18px;">
                                <div class="form-group">
                                    <label for="schedule_time_start" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Start Time *</label>
                                    <input type="time" id="schedule_time_start" name="schedule_time_start" required
                                           value="<?= esc($course['schedule_time_start'] ?? $course['schedule_time'] ?? old('schedule_time_start')) ?>"
                                           style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <?php if (isset($validation) && $validation->getError('schedule_time_start')): ?>
                                        <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('schedule_time_start') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="schedule_time_end" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">End Time *</label>
                                    <input type="time" id="schedule_time_end" name="schedule_time_end" required
                                           value="<?= esc($course['schedule_time_end'] ?? old('schedule_time_end')) ?>"
                                           style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px;">
                                    <?php if (isset($validation) && $validation->getError('schedule_time_end')): ?>
                                        <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('schedule_time_end') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="duration" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Class Duration (Auto)</label>
                                    <input type="text" id="duration" name="duration" readonly
                                           value="<?= esc($course['duration'] ?? old('duration', '2')) ?>"
                                           style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px; background: #f0f0f0;">
                                    <?php if (isset($validation) && $validation->getError('duration')): ?>
                                        <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('duration') ?></p>
                                    <?php endif; ?>
                                    <p style="margin-top: 5px; font-size: 12px; color: #666;">Automatically calculated from time range</p>
                                </div>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 18px;">
                                <div class="form-group">
                                    <label for="schedule_date_start" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">Start Schedule Date *</label>
                                    <input type="text" id="schedule_date_start" name="schedule_date_start" required
                                           value="<?= esc($course['schedule_date_start'] ?? old('schedule_date_start')) ?>"
                                           placeholder="Click to select start date"
                                           readonly
                                           style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px; cursor: pointer; background-color: #fff;">
                                    <?php if (isset($validation) && $validation->getError('schedule_date_start')): ?>
                                        <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('schedule_date_start') ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="schedule_date_end" style="display: block; margin-bottom: 6px; font-weight: 600; color: #333; font-size: 14px;">End Schedule Date *</label>
                                    <input type="text" id="schedule_date_end" name="schedule_date_end" required
                                           value="<?= esc($course['schedule_date_end'] ?? old('schedule_date_end')) ?>"
                                           placeholder="Click to select end date"
                                           readonly
                                           style="width: 100%; padding: 12px; border: 2px solid #999; border-radius: 3px; font-size: 14px; cursor: pointer; background-color: #fff;">
                                    <?php if (isset($validation) && $validation->getError('schedule_date_end')): ?>
                                        <p style="margin-top: 5px; font-size: 12px; color: #d32f2f;"><?= $validation->getError('schedule_date_end') ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

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

    <script>
        // Dynamic loading of semesters
        document.addEventListener('DOMContentLoaded', function() {
            const acadYearSelect = document.getElementById('acad_year_id');
            const semesterSelect = document.getElementById('semester_id');

            // Load semesters when academic year changes
            if (acadYearSelect) {
                acadYearSelect.addEventListener('change', function() {
                    const acadYearId = this.value;
                    
                    // Reset semester
                    semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                    
                    if (acadYearId) {
                        fetch('<?= base_url('course/get-semesters-by-academic-year') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: 'acad_year_id=' + acadYearId + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.semesters) {
                                data.semesters.forEach(semester => {
                                    const option = document.createElement('option');
                                    option.value = semester.id;
                                    option.textContent = semester.name;
                                    semesterSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error loading semesters:', error);
                        });
                    }
                });
            }

            // Teacher search functionality
            const teacherSearch = document.getElementById('teacher_search');
            const teacherIdInput = document.getElementById('teacher_id');
            const teacherResults = document.getElementById('teacher_results');
            const selectedTeacherDiv = document.getElementById('selected_teacher');
            const selectedTeacherName = document.getElementById('selected_teacher_name');
            const clearTeacherBtn = document.getElementById('clear_teacher');
            
            // Store teachers data
            const teachers = <?= json_encode(array_map(function($t) {
                return [
                    'id' => (int)$t['id'],
                    'name' => $t['name'],
                    'email' => $t['email']
                ];
            }, $teachers)) ?>;
            
            // Set initial value if teacher is already assigned
            <?php if(isset($course['teacher_id']) && $course['teacher_id']): ?>
                const initialTeacher = teachers.find(t => t.id == <?= $course['teacher_id'] ?>);
                if (initialTeacher) {
                    teacherSearch.value = initialTeacher.name + ' (' + initialTeacher.email + ')';
                    teacherIdInput.value = initialTeacher.id;
                    selectedTeacherName.textContent = initialTeacher.name + ' (' + initialTeacher.email + ')';
                    selectedTeacherDiv.style.display = '';
                }
            <?php endif; ?>
            
            if (teacherSearch && teacherIdInput && teacherResults) {
                // Show/hide results dropdown
                function showResults() {
                    teacherResults.style.display = 'block';
                }
                
                function hideResults() {
                    setTimeout(() => {
                        teacherResults.style.display = 'none';
                    }, 200);
                }
                
                // Filter and display results
                function filterTeachers(searchTerm) {
                    const term = searchTerm.toLowerCase().trim();
                    teacherResults.innerHTML = '';
                    
                    if (term === '') {
                        teacherResults.style.display = 'none';
                        return;
                    }
                    
                    const filtered = teachers.filter(teacher => 
                        teacher.name.toLowerCase().includes(term) || 
                        teacher.email.toLowerCase().includes(term)
                    );
                    
                    if (filtered.length === 0) {
                        teacherResults.innerHTML = '<div style="padding: 12px; color: #666; font-size: 13px;">No teachers found</div>';
                        showResults();
                        return;
                    }
                    
                    filtered.forEach(teacher => {
                        const div = document.createElement('div');
                        div.style.cssText = 'padding: 12px; cursor: pointer; border-bottom: 1px solid #ddd;';
                        div.onmouseover = function() { this.style.backgroundColor = '#f0f0f0'; };
                        div.onmouseout = function() { this.style.backgroundColor = 'white'; };
                        div.innerHTML = '<div style="font-weight: 600; color: #333;">' + teacher.name + '</div><div style="font-size: 11px; color: #666; margin-top: 2px;">' + teacher.email + '</div>';
                        div.addEventListener('click', function() {
                            teacherSearch.value = teacher.name + ' (' + teacher.email + ')';
                            teacherIdInput.value = teacher.id;
                            selectedTeacherName.textContent = teacher.name + ' (' + teacher.email + ')';
                            selectedTeacherDiv.style.display = '';
                            hideResults();
                        });
                        teacherResults.appendChild(div);
                    });
                    
                    showResults();
                }
                
                // Search input event
                teacherSearch.addEventListener('input', function() {
                    filterTeachers(this.value);
                });
                
                // Focus events
                teacherSearch.addEventListener('focus', function() {
                    if (this.value.trim() !== '') {
                        filterTeachers(this.value);
                    }
                });
                
                // Hide results when clicking outside
                document.addEventListener('click', function(e) {
                    if (!teacherSearch.contains(e.target) && !teacherResults.contains(e.target)) {
                        hideResults();
                    }
                });
                
                // Clear teacher selection
                if (clearTeacherBtn) {
                    clearTeacherBtn.addEventListener('click', function() {
                        teacherSearch.value = '';
                        teacherIdInput.value = '';
                        selectedTeacherDiv.style.display = 'none';
                        hideResults();
                    });
                }
            }

            // Auto-calculate duration from time range
            const startTimeInput = document.getElementById('schedule_time_start');
            const endTimeInput = document.getElementById('schedule_time_end');
            const durationInput = document.getElementById('duration');
            
            function calculateDuration() {
                if (startTimeInput && endTimeInput && durationInput) {
                    const startTime = startTimeInput.value;
                    const endTime = endTimeInput.value;
                    
                    if (startTime && endTime) {
                        // Parse time strings (HH:MM format)
                        const [startHours, startMinutes] = startTime.split(':').map(Number);
                        const [endHours, endMinutes] = endTime.split(':').map(Number);
                        
                        // Convert to minutes for easier calculation
                        const startTotalMinutes = startHours * 60 + startMinutes;
                        const endTotalMinutes = endHours * 60 + endMinutes;
                        
                        // Calculate difference in minutes
                        let diffMinutes = endTotalMinutes - startTotalMinutes;
                        
                        // Handle case where end time is next day (e.g., 11 PM to 1 AM)
                        if (diffMinutes < 0) {
                            diffMinutes += 24 * 60; // Add 24 hours
                        }
                        
                        // Calculate hours and minutes
                        const hours = Math.floor(diffMinutes / 60);
                        const minutes = diffMinutes % 60;
                        
                        // Format duration display (e.g., "4 hours (240 minutes)" or "3 hours 3 minutes (183 minutes)")
                        let durationText = '';
                        let durationValue = hours; // For form submission, use hours (round up if there are minutes)
                        
                        if (hours > 0 && minutes > 0) {
                            durationText = `${hours} hour${hours > 1 ? 's' : ''} ${minutes} minute${minutes > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            // Round up to next hour if there are minutes (for form validation)
                            durationValue = hours + 1;
                        } else if (hours > 0) {
                            durationText = `${hours} hour${hours > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            durationValue = hours;
                        } else {
                            durationText = `${minutes} minute${minutes > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            durationValue = 1; // Minimum 1 hour
                        }
                        
                        // Limit to 8 hours maximum
                        if (durationValue > 8) {
                            durationValue = 8;
                            durationText = `8 hours (480 minutes)`;
                        } else if (durationValue < 1) {
                            durationValue = 1;
                            durationText = `1 hour (60 minutes)`;
                        }
                        
                        // Set the display value (for form submission - rounded up hours)
                        durationInput.value = durationValue;
                        
                        // Update the helper text to show exact duration with minutes
                        // Find helper text by class or by looking for the paragraph after the input
                        let helperText = durationInput.parentElement.querySelector('.text-xs.text-gray-500');
                        if (!helperText) {
                            // Try to find the existing helper text paragraph
                            const allParagraphs = durationInput.parentElement.querySelectorAll('p');
                            for (let p of allParagraphs) {
                                if (p.textContent.includes('Automatically calculated') || p.textContent.includes('time range')) {
                                    helperText = p;
                                    break;
                                }
                            }
                        }
                        if (helperText) {
                            // Show exact duration in helper text
                            let exactDurationText = '';
                            if (hours > 0 && minutes > 0) {
                                exactDurationText = `${hours} hour${hours > 1 ? 's' : ''} ${minutes} minute${minutes > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            } else if (hours > 0) {
                                exactDurationText = `${hours} hour${hours > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            } else {
                                exactDurationText = `${minutes} minute${minutes > 1 ? 's' : ''} (${diffMinutes} minutes)`;
                            }
                            helperText.textContent = `Automatically calculated: ${exactDurationText}`;
                        }
                    }
                }
            }
            
            // Calculate duration when times change
            if (startTimeInput) {
                startTimeInput.addEventListener('change', calculateDuration);
                startTimeInput.addEventListener('input', calculateDuration);
            }
            
            if (endTimeInput) {
                endTimeInput.addEventListener('change', calculateDuration);
                endTimeInput.addEventListener('input', calculateDuration);
            }
            
            // Calculate on page load if both times are already set
            if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
                calculateDuration();
            }
            
            // Validation functions for special characters
            window.validateAlphanumericSpace = function(event) {
                const char = String.fromCharCode(event.which || event.keyCode);
                // Allow: letters, numbers, spaces, backspace, delete, tab, enter
                const allowedPattern = /^[a-zA-Z0-9\s]$/;
                const specialKeys = [8, 9, 13, 27, 46]; // backspace, tab, enter, escape, delete
                
                if (specialKeys.includes(event.keyCode) || event.ctrlKey || event.metaKey) {
                    return true;
                }
                
                if (!allowedPattern.test(char)) {
                    event.preventDefault();
                    return false;
                }
                return true;
            };
            
            window.validateInput = function(input) {
                const value = input.value;
                const errorId = input.id + '_error';
                const errorElement = document.getElementById(errorId);
                
                // Check for special characters (anything that's not alphanumeric or space)
                const specialCharPattern = /[^a-zA-Z0-9\s]/g;
                const hasSpecialChars = specialCharPattern.test(value);
                
                if (hasSpecialChars) {
                    // Remove special characters
                    input.value = value.replace(specialCharPattern, '');
                    
                    if (errorElement) {
                        errorElement.textContent = 'Special characters are not allowed and have been removed.';
                        errorElement.style.display = 'block';
                        setTimeout(() => {
                            errorElement.style.display = 'none';
                        }, 3000);
                    }
                } else if (errorElement) {
                    errorElement.style.display = 'none';
                }
            };
        });

        // Ensure CSRF token is always fresh before form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action*="edit-course"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Get fresh CSRF token from meta tag before submission
                    const csrfTokenName = '<?= csrf_token() ?>';
                    const csrfToken = document.querySelector('meta[name="' + csrfTokenName + '"]')?.getAttribute('content');
                    const csrfInput = form.querySelector('input[name="' + csrfTokenName + '"]');
                    
                    if (csrfToken && csrfInput) {
                        csrfInput.value = csrfToken;
                    }
                });
            }
            
            // Initialize Flatpickr for date inputs
            if (typeof flatpickr !== 'undefined') {
                const startDateInput = document.getElementById('schedule_date_start');
                const endDateInput = document.getElementById('schedule_date_end');
                
                if (startDateInput) {
                    flatpickr(startDateInput, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        allowInput: false,
                        clickOpens: true,
                        minDate: "today",
                        defaultDate: startDateInput.value || null
                    });
                }
                
                if (endDateInput) {
                    flatpickr(endDateInput, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "F j, Y",
                        allowInput: false,
                        clickOpens: true,
                        minDate: "today",
                        defaultDate: endDateInput.value || null
                    });
                }
            }
        });
    </script>
</body>
</html>

