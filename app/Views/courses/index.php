<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Learning Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
        
        /* Override Bootstrap styles */
        html {
            overflow: hidden !important;
            height: 100% !important;
            position: fixed !important;
            width: 100% !important;
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
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Prevent Bootstrap from affecting sidebar */
        #sidebar-container * {
            box-sizing: border-box !important;
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

        body.student-shell {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            height: 100% !important;
            width: 100% !important;
            position: fixed !important;
        }

        body.student-shell .search-panel {
            border: 3px solid #333;
            background: #fff;
            border-radius: 3px;
            box-shadow: 3px 3px 8px rgba(0,0,0,0.1);
        }

        body.student-shell .search-panel h6 {
            color: #333;
            font-size: 14px;
            font-weight: bold;
        }

        body.student-shell .card.course-card {
            border: 2px solid #999;
            border-radius: 3px;
            box-shadow: 3px 3px 8px rgba(0,0,0,0.1);
        }

        body.student-shell .card.course-card .badge {
            background: #e3f2fd;
            color: #1976d2;
            border: 2px solid #90caf9;
            border-radius: 3px;
        }

        body.student-shell .btn {
            border: 2px solid;
            font-weight: bold;
            border-radius: 3px;
        }

        body.student-shell .bg-primary {
            background: #1976d2 !important;
        }

        /* Select2 in Modal Fixes */
        .select2-container {
            z-index: 9999 !important;
        }
        
        .select2-dropdown {
            z-index: 9999 !important;
        }
        
        .select2-search__field {
            width: 100% !important;
            padding: 8px !important;
        }
        
        .select2-results__option {
            padding: 8px 12px !important;
            cursor: pointer !important;
        }
        
        .select2-results__option--highlighted {
            background-color: #0d6efd !important;
            color: white !important;
        }
    </style>
</head>
<body class="<?= session('role') === 'student' ? 'student-shell' : '' ?>">
    <!-- Sidebar Layout -->
    <div class="flex h-screen overflow-hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%;">
        <!-- Sidebar -->
        <div id="sidebar-container" class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-full h-full bg-white border-r border-gray-200" style="overflow-y: auto;">
                <div class="flex items-center justify-center h-16 px-4 lms-header shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
            <!-- Courses Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="container" style="max-width: 100%; margin: 0; padding: 20px;">
                    <!-- Page Header -->
                    <div class="page-header" style="background: white; padding: 25px; border: 3px solid #333; border-radius: 3px; margin-bottom: 25px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h1 style="font-size: 28px; color: #333; margin-bottom: 8px;">Manage Courses</h1>
                                <p style="color: #666; font-size: 14px;">View and manage all courses in the system</p>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <?php if(session('role') == 'teacher' || session('role') == 'admin'): ?>
                                    <a href="<?= base_url('create-course') ?>" class="btn" style="background: #1976d2; color: white; border-color: #1565c0; padding: 8px 15px; border: 2px solid; border-radius: 3px; font-weight: bold; font-size: 13px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 0;">
                                        <i class="fas fa-plus"></i> Add New Course
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- Flash Messages -->
                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #d4edda; color: #155724; border-color: #c3e6cb;">
                            <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" style="padding: 12px; border-radius: 3px; margin-bottom: 20px; border: 2px solid; display: flex; align-items: center; background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                            <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Courses List Grouped by Program -->
                    <div id="coursesContainer">
                        <?php if (!empty($groupedCourses)): ?>
                            <?php foreach ($groupedCourses as $programKey => $programData): ?>
                                <!-- Program Section Header -->
                                <div class="program-section" style="margin-bottom: 30px;">
                                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 3px solid #333;">
                                        <h2 style="color: white; margin: 0; font-size: 24px; font-weight: bold; display: flex; align-items: center;">
                                            <i class="fas fa-graduation-cap" style="margin-right: 12px; font-size: 28px;"></i>
                                            <?= esc($programData['program_name']) ?>
                                            <span style="margin-left: auto; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                                                <?= count($programData['courses']) ?> <?= count($programData['courses']) == 1 ? 'Course' : 'Courses' ?>
                                            </span>
                                        </h2>
                                    </div>
                                    
                                    <!-- Courses in this Program -->
                                    <div class="row g-4">
                                        <?php foreach ($programData['courses'] as $course): ?>
                                <div class="col-md-4 mb-4" data-course-item>
                                    <div class="card course-card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0"><?= esc($course['title'] ?? 'Untitled Course') ?></h5>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="card-text flex-grow-1"><?= esc($course['description'] ?? 'No description provided.') ?></p>
                                            
                                            <?php if (!empty($course['course_number'])): ?>
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-hashtag me-2"></i>
                                                <span><strong>Course Code:</strong> <?= esc($course['course_number']) ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['acad_year_name'])): ?>
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                <span><strong>Academic Year:</strong> <?= esc($course['acad_year_name']) ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['semester_name'])): ?>
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-calendar-week me-2"></i>
                                                <span><strong>Semester:</strong> <?= esc($course['semester_name']) ?></span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['schedule_time_start']) || !empty($course['schedule_time']) || !empty($course['schedule_date']) || !empty($course['duration'])): ?>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-clock me-2"></i>
                                                <span>
                                                    <?php 
                                                    $startTime = $course['schedule_time_start'] ?? $course['schedule_time'] ?? '';
                                                    $endTime = $course['schedule_time_end'] ?? '';
                                                    $hasTime = !empty($startTime);
                                                    $hasDuration = !empty($course['duration']);
                                                    $hasDate = !empty($course['schedule_date']);
                                                    
                                                    // Display Time (Class Time) - Show if available
                                                    if ($hasTime): 
                                                        // Format time for display (convert 24h to 12h)
                                                        $startFormatted = date('g:i A', strtotime($startTime));
                                                        if ($endTime) {
                                                            $endFormatted = date('g:i A', strtotime($endTime));
                                                            echo '<strong>Class Time:</strong> ' . esc($startFormatted) . ' - ' . esc($endFormatted);
                                                        } else {
                                                            echo '<strong>Class Time:</strong> ' . esc($startFormatted);
                                                        }
                                                    endif; 
                                                    
                                                    // Display Duration
                                                    if ($hasTime && $endTime): 
                                                        // Calculate duration from start and end times
                                                        $startTimestamp = strtotime($startTime);
                                                        $endTimestamp = strtotime($endTime);
                                                        $diffMinutes = round(($endTimestamp - $startTimestamp) / 60);
                                                        $hours = floor($diffMinutes / 60);
                                                        $minutes = $diffMinutes % 60;
                                                        
                                                        if ($hasTime): ?> | <?php endif; ?>
                                                        <strong>Duration:</strong> 
                                                        <?php 
                                                        if ($hours > 0 && $minutes > 0) {
                                                            echo esc($hours) . ' hour' . ($hours > 1 ? 's' : '') . ' ' . esc($minutes) . ' minute' . ($minutes > 1 ? 's' : '');
                                                        } else if ($hours > 0) {
                                                            echo esc($hours) . ' hour' . ($hours > 1 ? 's' : '');
                                                        } else {
                                                            echo esc($minutes) . ' minute' . ($minutes > 1 ? 's' : '');
                                                        }
                                                        ?>
                                                    <?php elseif ($hasDuration): ?>
                                                        <?php if ($hasTime): ?> | <?php endif; ?>
                                                        <strong>Duration:</strong> <?= esc($course['duration']) ?> <?= $course['duration'] == 1 ? 'Hour' : 'Hours' ?>
                                                    <?php endif; ?>
                                                    
                                                    <?php // Display Date ?>
                                                    <?php if ($hasDate): ?>
                                                        <?php if ($hasTime || $hasDuration): ?> | <?php endif; ?>
                                                        <strong>Date:</strong> <?= date('M d, Y', strtotime($course['schedule_date'])) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php // Display Start and End Schedule Dates ?>
                                            <?php 
                                            $dateStart = $course['schedule_date_start'] ?? '';
                                            $dateEnd = $course['schedule_date_end'] ?? '';
                                            if ($dateStart || $dateEnd): 
                                            ?>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                <span>
                                                    <?php if ($dateStart && $dateEnd): ?>
                                                        <strong>Schedule:</strong> <?= date('M d, Y', strtotime($dateStart)) ?> - <?= date('M d, Y', strtotime($dateEnd)) ?>
                                                    <?php elseif ($dateStart): ?>
                                                        <strong>Start Date:</strong> <?= date('M d, Y', strtotime($dateStart)) ?>
                                                    <?php elseif ($dateEnd): ?>
                                                        <strong>End Date:</strong> <?= date('M d, Y', strtotime($dateEnd)) ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($course['teacher_id'])): ?>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-user-tie me-2"></i>
                                                <span>
                                                    <strong>Teacher:</strong> 
                                                    <?= !empty($course['teacher_name']) ? esc($course['teacher_name']) : 'Unknown' ?>
                                                    (ID: <?= esc($course['teacher_id']) ?>)
                                                </span>
                                            </div>
                                            <?php endif; ?>
                                            <div class="text-muted small mb-4">
                                                <i class="fas fa-calendar me-2"></i>
                                                <span>Created: <?= date('M j, Y', strtotime($course['created_at'] ?? 'now')) ?></span>
                                            </div>
                                            <div class="mt-auto">
                                                <?php if(session('role') == 'admin'): ?>
                                                    <div class="d-grid gap-2">
                                                        <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-primary">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <button type="button" class="btn btn-outline-info" onclick="openAssignTeacherModal(<?= $course['id'] ?>, <?= htmlspecialchars(json_encode($course['title']), ENT_QUOTES, 'UTF-8') ?>, <?= $course['teacher_id'] ?? 0 ?>)">
                                                            <i class="fas fa-user-tie me-1"></i> Assign Teacher
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info" onclick="openViewStudentsModal(<?= $course['id'] ?>, <?= htmlspecialchars(json_encode($course['title']), ENT_QUOTES, 'UTF-8') ?>)">
                                                            <i class="fas fa-users me-1"></i> View Students
                                                        </button>
                                                        <button type="button" class="btn btn-outline-success" onclick="openEnrollStudentModal(<?= $course['id'] ?>, <?= htmlspecialchars(json_encode($course['title']), ENT_QUOTES, 'UTF-8') ?>)">
                                                            <i class="fas fa-user-plus me-1"></i> Enroll Student
                                                        </button>
                                                        <a href="<?= base_url('edit-course/' . $course['id']) ?>" class="btn btn-outline-secondary">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteCourse(<?= $course['id'] ?>, '<?= htmlspecialchars(addslashes($course['title']), ENT_QUOTES, 'UTF-8') ?>')">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                <?php elseif(session('role') == 'student'): ?>
                                                    <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-primary w-100">
                                                        <i class="fas fa-info-circle me-1"></i> View Details
                                                    </a>
                                                <?php else: ?>
                                                    <div class="d-grid gap-2">
                                                        <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-primary">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <a href="<?= base_url('edit-course/' . $course['id']) ?>" class="btn btn-outline-secondary">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12" data-course-item>
                                <div class="text-center py-5 bg-white rounded shadow-sm">
                                    <i class="fas fa-book-open text-4xl text-gray-300 mb-3"></i>
                                    <h3 class="h5 mb-2">No Courses Found</h3>
                                    <p class="text-muted mb-4">
                                        <?php if(session('role') == 'teacher'): ?>
                                            You haven't created any courses yet. Get started by creating your first course.
                                        <?php elseif(session('role') == 'student'): ?>
                                            No courses are available for enrollment at the moment.
                                        <?php else: ?>
                                            No courses have been created in the system yet.
                                        <?php endif; ?>
                                    </p>
                                    <?php if(session('role') == 'teacher'): ?>
                                        <a href="<?= base_url('create-course') ?>" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i> Create Your First Course
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            const searchEndpoint = '<?= base_url('courses/search') ?>';
            const courseViewBase = '<?= base_url('course') ?>';

            $('#searchInput').on('keyup', function () {
                const value = $(this).val().toLowerCase();
                $('.course-card').each(function () {
                    const matches = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).closest('[data-course-item]').toggle(matches);
                });
            });

            $('#searchForm').on('submit', function (e) {
                e.preventDefault();
                const searchTerm = $('#searchInput').val();

                $.get(searchEndpoint, { search_term: searchTerm }, function (data) {
                    const $container = $('#coursesContainer');
                    $container.empty();

                    if (Array.isArray(data) && data.length) {
                        $.each(data, function (index, course) {
                            const description = course.description ? course.description : 'No description provided.';
                            const title = course.title ? course.title : 'Untitled Course';
                            const teacherId = course.teacher_id ? course.teacher_id : null;
                            const teacherName = course.teacher_name ? course.teacher_name : 'Unknown';
                            const createdAt = course.created_at ? new Date(course.created_at).toLocaleDateString() : 'Recently added';

                            const card = `
                                <div class="col-md-4 mb-4" data-course-item>
                                    <div class="card course-card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">${title}</h5>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="card-text flex-grow-1">${description}</p>
                                            ${teacherId ? `
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-user-tie me-2"></i>
                                                <span><strong>Teacher:</strong> ${teacherName} (ID: ${teacherId})</span>
                                            </div>
                                            ` : ''}
                                            <div class="text-muted small mb-4">
                                                <i class="fas fa-calendar me-2"></i>
                                                <span>Created: ${createdAt}</span>
                                            </div>
                                            <a href="${courseViewBase}/${course.id}" class="btn btn-primary mt-auto">
                                                <i class="fas fa-eye me-1"></i> View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>`;

                            $container.append(card);
                        });
                    } else {
                        $container.html(`
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    No courses found matching your search.
                                </div>
                            </div>
                        `);
                    }
                }).fail(function () {
                    $('#coursesContainer').html(`
                        <div class="col-12">
                            <div class="alert alert-danger text-center">
                                An error occurred while searching. Please try again.
                            </div>
                        </div>
                    `);
                });
            });
        });
    </script>

    <!-- Admin Modals for Course Management -->
    <?php if(session('role') == 'admin'): ?>
    <!-- Assign Teacher Modal -->
    <div class="modal fade" id="assignTeacherModal" tabindex="-1" aria-labelledby="assignTeacherModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTeacherModalLabel">Assign Teacher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><strong>Course:</strong> <span id="assignTeacherCourseTitle"></span></p>
                    <form id="assignTeacherForm">
                        <?= csrf_field() ?>
                        <input type="hidden" id="assignTeacherCourseId" name="course_id">
                        <div class="mb-3">
                            <label for="assignTeacherSelect" class="form-label">Select Teacher</label>
                            <select class="form-select" id="assignTeacherSelect" name="teacher_id" required>
                                <option value="">Loading teachers...</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitAssignTeacher()">Assign Teacher</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Students Modal -->
    <div class="modal fade" id="viewStudentsModal" tabindex="-1" aria-labelledby="viewStudentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentsModalLabel">Enrolled Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><strong>Course:</strong> <span id="viewStudentsCourseTitle"></span></p>
                    <div id="viewStudentsLoading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading students...</p>
                    </div>
                    <div id="viewStudentsContent" style="display: none;">
                        <div id="viewStudentsList"></div>
                    </div>
                    <div id="viewStudentsError" class="alert alert-danger" style="display: none;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enroll Student Modal -->
    <div class="modal fade" id="enrollStudentModal" tabindex="-1" aria-labelledby="enrollStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enrollStudentModalLabel">Enroll Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3"><strong>Course:</strong> <span id="enrollStudentCourseTitle"></span></p>
                    <form id="enrollStudentForm">
                        <?= csrf_field() ?>
                        <input type="hidden" id="enrollStudentCourseId" name="course_id">
                        <div class="mb-3">
                            <label for="enrollStudentSelect" class="form-label">Select Student</label>
                            <select class="form-select" id="enrollStudentSelect" name="student_id" required>
                                <option value="">Loading students...</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitEnrollStudent()">Enroll Student</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load teachers for assignment
        function loadTeachers() {
            $.get('<?= base_url('courses/getAllTeachers') ?>', function(data) {
                // This will be implemented if needed, for now we'll get from user endpoint
            });
        }

        // Open Assign Teacher Modal
        function openAssignTeacherModal(courseId, courseTitle, currentTeacherId) {
            $('#assignTeacherCourseId').val(courseId);
            $('#assignTeacherCourseTitle').text(courseTitle);
            
            // Reset the select
            const select = $('#assignTeacherSelect');
            select.empty();
            select.append('<option value="">Loading teachers...</option>');
            
            // Destroy existing Select2 instance if it exists
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
            
            // Show modal first
            $('#assignTeacherModal').modal('show');
            
            // Load teachers via AJAX
            $.get('<?= base_url('courses/getAllTeachers') ?>', function(response) {
                if (response.success) {
                    select.empty();
                    select.append('<option value="">Select a teacher</option>');
                    response.teachers.forEach(function(teacher) {
                        const selected = teacher.id == currentTeacherId ? 'selected' : '';
                        select.append(`<option value="${teacher.id}" ${selected} data-teacher-name="${teacher.name}" data-teacher-email="${teacher.email}">${teacher.name} (ID: ${teacher.id})</option>`);
                    });
                    
                    // Initialize Select2 with search functionality
                    select.select2({
                        theme: 'bootstrap-5',
                        placeholder: 'Search for a teacher...',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#assignTeacherModal'),
                        minimumResultsForSearch: 0, // Always show search box
                        templateResult: function(data) {
                            if (!data.id) {
                                return data.text;
                            }
                            const $option = $(data.element);
                            const teacherName = $option.data('teacher-name') || data.text;
                            const teacherId = data.id;
                            const teacherEmail = $option.data('teacher-email') || '';
                            return $('<span><strong>' + teacherName + '</strong> (ID: ' + teacherId + ')<br><small class="text-muted">' + teacherEmail + '</small></span>');
                        },
                        templateSelection: function(data) {
                            if (!data.id) {
                                return data.text;
                            }
                            const $option = $(data.element);
                            const teacherName = $option.data('teacher-name') || data.text;
                            const teacherId = data.id;
                            return teacherName + ' (ID: ' + teacherId + ')';
                        },
                        language: {
                            noResults: function() {
                                return "No teachers found";
                            },
                            searching: function() {
                                return "Searching...";
                            }
                        }
                    });
                    
                    // Ensure search field is focusable and clickable
                    select.on('select2:open', function() {
                        setTimeout(function() {
                            $('.select2-search__field').focus();
                        }, 100);
                    });
                } else {
                    select.html('<option value="">Error loading teachers</option>');
                }
            }).fail(function() {
                select.html('<option value="">Error loading teachers. Please refresh the page.</option>');
            });
        }

        // Submit Assign Teacher
        function submitAssignTeacher() {
            const courseId = $('#assignTeacherCourseId').val();
            const teacherId = $('#assignTeacherSelect').val();
            
            if (!courseId || !teacherId) {
                alert('Please select a teacher');
                return;
            }
            
            // Get CSRF token from form or meta tag
            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfInput = $('#assignTeacherForm input[name="' + csrfTokenName + '"]');
            const csrfToken = csrfInput.length ? csrfInput.val() : $('meta[name="' + csrfTokenName + '"]').attr('content');
            
            const formData = {
                course_id: courseId,
                teacher_id: teacherId
            };
            
            // Add CSRF token
            formData[csrfTokenName] = csrfToken;

            $.ajax({
                url: '<?= base_url('courses/assignTeacher') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Update CSRF token if provided
                    if (response.csrf_hash) {
                        if (csrfInput.length) {
                            csrfInput.val(response.csrf_hash);
                        }
                        $('meta[name="' + csrfTokenName + '"]').attr('content', response.csrf_hash);
                    }
                    
                    if (response.success) {
                        alert('Teacher assigned successfully!');
                        $('#assignTeacherModal').modal('hide');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'Failed to assign teacher'));
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    
                    // Update CSRF token if provided in error response
                    if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                        if (csrfInput.length) {
                            csrfInput.val(xhr.responseJSON.csrf_hash);
                        }
                        $('meta[name="' + csrfTokenName + '"]').attr('content', xhr.responseJSON.csrf_hash);
                    }
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 403 || (xhr.responseText && (xhr.responseText.includes('not allowed') || xhr.responseText.includes('CSRF')))) {
                        errorMsg = 'CSRF token expired. Please refresh the page and try again.';
                    }
                    alert(errorMsg);
                }
            });
        }

        // Open Enroll Student Modal
        function openEnrollStudentModal(courseId, courseTitle) {
            $('#enrollStudentCourseId').val(courseId);
            $('#enrollStudentCourseTitle').text(courseTitle);
            
            // Reset the select
            const select = $('#enrollStudentSelect');
            select.empty();
            select.append('<option value="">Loading students...</option>');
            
            // Destroy existing Select2 instance if it exists
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
            
            // Show modal first
            $('#enrollStudentModal').modal('show');
            
            // Load students
            $.ajax({
                url: '<?= base_url('courses/getAllStudents') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.students) {
                        select.empty();
                        select.append('<option value="">Select a student</option>');
                        response.students.forEach(function(student) {
                            select.append(`<option value="${student.id}">${student.name} (${student.email})</option>`);
                        });
                        
                        // Initialize Select2 with search functionality after modal is shown
                        // Use dropdownParent to ensure dropdown appears above modal
                        select.select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Search for a student...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('#enrollStudentModal'),
                            minimumResultsForSearch: 0, // Always show search box
                            language: {
                                noResults: function() {
                                    return "No students found";
                                },
                                searching: function() {
                                    return "Searching...";
                                }
                            }
                        });
                        
                        // Ensure search field is focusable and clickable
                        select.on('select2:open', function() {
                            setTimeout(function() {
                                $('.select2-search__field').focus();
                            }, 100);
                        });
                    } else {
                        select.html('<option value="">No students available</option>');
                    }
                },
                error: function(xhr) {
                    select.html('<option value="">Error loading students. Please try again.</option>');
                    console.error('Error loading students:', xhr);
                }
            });
        }

        // Clean up Select2 when modals are closed
        $('#enrollStudentModal').on('hidden.bs.modal', function () {
            const select = $('#enrollStudentSelect');
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
        });
        
        $('#assignTeacherModal').on('hidden.bs.modal', function () {
            const select = $('#assignTeacherSelect');
            if (select.hasClass('select2-hidden-accessible')) {
                select.select2('destroy');
            }
        });

        // Submit Enroll Student
        function submitEnrollStudent() {
            const courseId = $('#enrollStudentCourseId').val();
            const studentId = $('#enrollStudentSelect').val();
            
            if (!courseId || !studentId) {
                alert('Please select a student.');
                return;
            }
            
            // Get CSRF token from the form
            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfTokenValue = $('#enrollStudentForm input[name="' + csrfTokenName + '"]').val();
            
            const formData = {
                course_id: courseId,
                student_id: studentId
            };
            
            // Add CSRF token
            formData[csrfTokenName] = csrfTokenValue;

            $.ajax({
                url: '<?= base_url('courses/adminEnrollStudent') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Update CSRF token if provided
                    if (response.csrf_hash) {
                        $('#enrollStudentForm input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                    }
                    
                    if (response.success) {
                        alert('✅ SUCCESS: Student enrolled successfully!');
                        $('#enrollStudentModal').modal('hide');
                        location.reload();
                    } else {
                        // Show prominent error message
                        const errorMsg = response.message || 'Failed to enroll student';
                        alert('⚠️ ' + errorMsg);
                        
                        // Show flash message for program restriction errors
                        if (errorMsg.includes('NOT enrolled') || errorMsg.includes('enrolled in') || errorMsg.includes('ENROLLMENT FAILED')) {
                            // Create flash message
                            const flashHtml = `
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                    <strong><i class="fas fa-exclamation-triangle mr-2"></i>Enrollment Failed!</strong><br>
                                    <span>${errorMsg}</span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            $('body').append(flashHtml);
                            
                            // Auto-remove after 10 seconds
                            setTimeout(function() {
                                $('.alert-danger').fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 10000);
                        }
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    
                    // Handle CSRF token error
                    if (xhr.status === 403 || xhr.status === 400) {
                        errorMsg = 'Session expired. Please refresh the page and try again.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const errorData = JSON.parse(xhr.responseText);
                            if (errorData.message) {
                                errorMsg = errorData.message;
                            }
                        } catch (e) {
                            // If not JSON, use default message
                        }
                    }
                    
                    alert(errorMsg);
                    
                    // Update CSRF token if provided
                    if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                        $('#enrollStudentForm input[name="' + csrfTokenName + '"]').val(xhr.responseJSON.csrf_hash);
                    }
                }
            });
        }
    </script>
    <?php endif; ?>
    
    <script>
        // Prevent any scrolling or movement
        (function() {
            'use strict';
            
            // Lock body scroll
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
            document.body.style.height = '100%';
            document.body.style.top = '0';
            document.body.style.left = '0';
            
            // Lock html scroll
            document.documentElement.style.overflow = 'hidden';
            document.documentElement.style.position = 'fixed';
            document.documentElement.style.width = '100%';
            document.documentElement.style.height = '100%';
            
            // Ensure sidebar stays fixed
            const sidebar = document.getElementById('sidebar-container');
            if (sidebar) {
                sidebar.style.position = 'fixed';
                sidebar.style.left = '0';
                sidebar.style.top = '0';
                sidebar.style.bottom = '0';
                sidebar.style.width = '256px';
                sidebar.style.zIndex = '1000';
                sidebar.style.transform = 'translateX(0)';
            }
            
            // Prevent scroll on window
            window.addEventListener('scroll', function(e) {
                e.preventDefault();
                window.scrollTo(0, 0);
            }, { passive: false });
            
            // Prevent scroll on touch devices
            document.addEventListener('touchmove', function(e) {
                if (e.target.closest('#sidebar-container')) {
                    return; // Allow scroll inside sidebar
                }
                e.preventDefault();
            }, { passive: false });
        })();
        
        // Open View Students Modal
        function openViewStudentsModal(courseId, courseTitle) {
            $('#viewStudentsCourseTitle').text(courseTitle);
            $('#viewStudentsModal').data('course-id', courseId);
            $('#viewStudentsLoading').show();
            $('#viewStudentsContent').hide();
            $('#viewStudentsError').hide();
            
            // Fetch enrolled students
            $.ajax({
                url: '<?= base_url('course/') ?>' + courseId + '/enrollments',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#viewStudentsLoading').hide();
                    
                    if (response.success && response.enrollments) {
                        renderViewStudentsList(response.enrollments, courseId);
                        $('#viewStudentsContent').show();
                    } else {
                        $('#viewStudentsError').text(response.message || 'Failed to load students').show();
                    }
                },
                error: function(xhr) {
                    $('#viewStudentsLoading').hide();
                    let errorMsg = 'Failed to load students. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#viewStudentsError').text(errorMsg).show();
                }
            });
            
            $('#viewStudentsModal').modal('show');
        }
        
        // Render students list
        function renderViewStudentsList(enrollments, courseId) {
            const accepted = enrollments.accepted || [];
            const pending = enrollments.pending || [];
            const rejected = enrollments.rejected || [];
            
            let html = '<div class="mb-3">';
            html += '<h6 class="mb-2">Summary:</h6>';
            html += '<div class="d-flex gap-2 mb-3">';
            html += '<span class="badge bg-success">Accepted: ' + (enrollments.summary?.accepted || accepted.length) + '</span>';
            html += '<span class="badge bg-warning">Pending: ' + (enrollments.summary?.pending || pending.length) + '</span>';
            html += '<span class="badge bg-danger">Rejected: ' + (enrollments.summary?.rejected || rejected.length) + '</span>';
            html += '</div>';
            html += '</div>';
            
            // Accepted Students
            if (accepted.length > 0) {
                html += '<div class="mb-4">';
                html += '<h6 class="text-success mb-3"><i class="fas fa-check-circle me-2"></i>Accepted Students (' + accepted.length + ')</h6>';
                html += '<div class="list-group">';
                accepted.forEach(function(student) {
                    html += '<div class="list-group-item d-flex justify-content-between align-items-center">';
                    html += '<div>';
                    html += '<strong>' + escapeHtml(student.student_name || 'Unknown') + '</strong><br>';
                    html += '<small class="text-muted">' + escapeHtml(student.student_email || '') + '</small><br>';
                    html += '<small class="text-muted">Enrolled: ' + formatDate(student.enrollment_date) + '</small>';
                    html += '</div>';
                    html += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmRemoveStudent(' + student.user_id + ', ' + courseId + ', \'' + escapeHtml(student.student_name || 'Student') + '\', \'' + escapeHtml($('#viewStudentsCourseTitle').text()) + '\')">';
                    html += '<i class="fas fa-user-minus me-1"></i> Remove';
                    html += '</button>';
                    html += '</div>';
                });
                html += '</div>';
                html += '</div>';
            }
            
            // Pending Students
            if (pending.length > 0) {
                html += '<div class="mb-4">';
                html += '<h6 class="text-warning mb-3"><i class="fas fa-clock me-2"></i>Pending Requests (' + pending.length + ')</h6>';
                html += '<div class="list-group">';
                pending.forEach(function(student) {
                    html += '<div class="list-group-item d-flex justify-content-between align-items-center">';
                    html += '<div>';
                    html += '<strong>' + escapeHtml(student.student_name || 'Unknown') + '</strong><br>';
                    html += '<small class="text-muted">' + escapeHtml(student.student_email || '') + '</small><br>';
                    html += '<small class="text-muted">Requested: ' + formatDate(student.enrollment_date) + '</small>';
                    html += '</div>';
                    html += '<span class="badge bg-warning">Pending</span>';
                    html += '</div>';
                });
                html += '</div>';
                html += '</div>';
            }
            
            // Rejected Students
            if (rejected.length > 0) {
                html += '<div class="mb-4">';
                html += '<h6 class="text-danger mb-3"><i class="fas fa-times-circle me-2"></i>Rejected Requests (' + rejected.length + ')</h6>';
                html += '<div class="list-group">';
                rejected.forEach(function(student) {
                    html += '<div class="list-group-item d-flex justify-content-between align-items-center">';
                    html += '<div>';
                    html += '<strong>' + escapeHtml(student.student_name || 'Unknown') + '</strong><br>';
                    html += '<small class="text-muted">' + escapeHtml(student.student_email || '') + '</small>';
                    html += '</div>';
                    html += '<span class="badge bg-danger">Rejected</span>';
                    html += '</div>';
                });
                html += '</div>';
                html += '</div>';
            }
            
            if (accepted.length === 0 && pending.length === 0 && rejected.length === 0) {
                html += '<div class="text-center py-4 text-muted">';
                html += '<i class="fas fa-users fa-3x mb-3 text-muted"></i>';
                html += '<p>No students enrolled in this course yet.</p>';
                html += '</div>';
            }
            
            $('#viewStudentsList').html(html);
        }
        
        // Remove student confirmation
        function confirmRemoveStudent(studentId, courseId, studentName, courseTitle) {
            if (!confirm('⚠️ WARNING: Are you sure you want to remove this student from the course?\n\nStudent: ' + studentName + '\nCourse: ' + courseTitle + '\n\nThis action will permanently remove the student from this course.\n\nClick OK to confirm removal, or Cancel to abort.')) {
                return;
            }
            
            // Double confirmation for safety
            if (!confirm('⚠️ FINAL CONFIRMATION\n\nAre you absolutely sure you want to remove "' + studentName + '" from "' + courseTitle + '"?\n\nThis is your last chance to cancel.')) {
                return;
            }
            
            // Get CSRF token
            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfTokenValue = $('input[name="' + csrfTokenName + '"]').val() || $('meta[name="csrf-token"]').attr('content');
            
            const formData = {
                student_id: studentId,
                course_id: courseId
            };
            formData[csrfTokenName] = csrfTokenValue;
            
            $.ajax({
                url: '<?= base_url('course/admin-remove-student') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    // Update CSRF token if provided
                    if (response.csrf_hash) {
                        $('input[name="' + csrfTokenName + '"]').val(response.csrf_hash);
                    }
                    
                    if (response.success) {
                        alert('Student removed successfully!');
                        // Reload the students list
                        const courseId = $('#viewStudentsModal').data('course-id');
                        const courseTitle = $('#viewStudentsCourseTitle').text();
                        openViewStudentsModal(courseId, courseTitle);
                    } else {
                        alert('Error: ' + (response.message || 'Failed to remove student'));
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                    
                    // Update CSRF token if provided
                    if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                        $('input[name="' + csrfTokenName + '"]').val(xhr.responseJSON.csrf_hash);
                    }
                }
            });
        }
        
        // Helper functions
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text ? text.replace(/[&<>"']/g, function(m) { return map[m]; }) : '';
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }
        
        // Store course ID when opening modal
        $('#viewStudentsModal').on('show.bs.modal', function() {
            // This will be set by openViewStudentsModal
        });
        
        // Delete course confirmation function
        function confirmDeleteCourse(courseId, courseTitle) {
            if (!confirm('⚠️ WARNING: Are you sure you want to delete this course?\n\nCourse: ' + courseTitle + '\n\nThis action will permanently delete:\n- The course\n- All enrollments\n- All course materials\n\nThis action CANNOT be undone!\n\nClick OK to confirm deletion, or Cancel to abort.')) {
                return;
            }
            
            // Double confirmation for safety
            if (!confirm('⚠️ FINAL CONFIRMATION\n\nAre you absolutely sure you want to delete "' + courseTitle + '"?\n\nThis is your last chance to cancel.')) {
                return;
            }
            
            // Create a form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('course/delete/') ?>' + courseId;
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
