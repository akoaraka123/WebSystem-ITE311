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
                        <div class="p-4 mb-6 text-red-700 bg-red-100 border-l-4 border-red-500 rounded shadow-lg" style="animation: slideIn 0.3s ease-out;">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold"><?= session()->getFlashdata('error') ?></p>
                                </div>
                            </div>
                        </div>
                        <script>
                            // Auto-scroll to error message
                            document.addEventListener('DOMContentLoaded', function() {
                                const errorDiv = document.querySelector('.bg-red-100');
                                if (errorDiv) {
                                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }
                            });
                        </script>
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
                                           placeholder="e.g., Introduction to Web Development"
                                           onkeypress="return validateAlphanumericSpace(event)"
                                           oninput="validateInput(this)">
                                    <?php if (isset($validation) && $validation->getError('title')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('title') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Only letters, numbers, and spaces are allowed. Special characters are not permitted.</p>
                                    <p id="title_error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Course Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Course Description *</label>
                                    <textarea id="description" name="description" rows="6" required
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                              placeholder="Provide a detailed description of your course..."
                                              onkeypress="return validateAlphanumericSpace(event)"
                                              oninput="validateInput(this)"><?= old('description') ?></textarea>
                                    <?php if (isset($validation) && $validation->getError('description')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('description') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Minimum 10 characters required. Only letters, numbers, and spaces are allowed.</p>
                                    <p id="description_error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Teacher Assignment (Admin Only) -->
                                <?php if(session('role') == 'admin' && !empty($teachers)): ?>
                                <div>
                                    <label for="teacher_search" class="block text-sm font-medium text-gray-700">Assign Teacher *</label>
                                    <div class="relative">
                                        <input type="text" id="teacher_search" placeholder="Search teacher by name or email..." 
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                               autocomplete="off">
                                        <input type="hidden" id="teacher_id" name="teacher_id" value="<?= old('teacher_id', '') ?>" required>
                                        <div id="teacher_results" class="hidden absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                    <div id="selected_teacher" class="mt-2 p-2 bg-gray-50 rounded-md text-sm text-gray-700 hidden">
                                        <span class="font-medium">Selected: </span><span id="selected_teacher_name"></span>
                                        <button type="button" id="clear_teacher" class="ml-2 text-red-600 hover:text-red-800 text-xs">Clear</button>
                                    </div>
                                    <?php if (isset($validation) && $validation->getError('teacher_id')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('teacher_id') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Type to search for a teacher</p>
                                </div>
                                <?php endif; ?>

                                <!-- Program Assignment -->
                                <?php if(!empty($programs)): ?>
                                <div>
                                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program</label>
                                    <select id="program_id" name="program_id"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">Select a program (optional)</option>
                                        <?php foreach($programs as $program): ?>
                                            <option value="<?= $program['id'] ?>" <?= old('program_id') == $program['id'] ? 'selected' : '' ?>>
                                                <?= esc($program['code']) ?> - <?= esc($program['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Select the program this course belongs to (e.g., BSIT, BSBA)</p>
                                </div>
                                <?php endif; ?>

                                <!-- Academic Year -->
                                <div>
                                    <label for="acad_year_id" class="block text-sm font-medium text-gray-700">Academic Year (Taon ng Akademiko) *</label>
                                    <select id="acad_year_id" name="acad_year_id" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">Select Academic Year</option>
                                        <?php if(isset($academicYears) && !empty($academicYears)): ?>
                                            <?php foreach($academicYears as $acadYear): ?>
                                                <option value="<?= $acadYear['id'] ?>" <?= old('acad_year_id') == $acadYear['id'] ? 'selected' : '' ?>>
                                                    <?= esc($acadYear['display_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($validation) && $validation->getError('acad_year_id')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('acad_year_id') ?></p>
                                    <?php endif; ?>
                                </div>

                                <!-- Semester -->
                                <div>
                                    <label for="semester_id" class="block text-sm font-medium text-gray-700">Semester (Semestre) *</label>
                                    <select id="semester_id" name="semester_id" required
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <option value="">Select Academic Year first</option>
                                        <?php if(isset($semesters) && !empty($semesters)): ?>
                                            <?php foreach($semesters as $semester): ?>
                                                <option value="<?= $semester['id'] ?>" <?= old('semester_id') == $semester['id'] ? 'selected' : '' ?>>
                                                    <?= esc($semester['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($validation) && $validation->getError('semester_id')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('semester_id') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Please select Academic Year first</p>
                                </div>

                                <!-- Course Number (CN) -->
                                <div>
                                    <label for="course_number" class="block text-sm font-medium text-gray-700">Course Number / Section Code (CN) *</label>
                                    <input type="text" id="course_number" name="course_number" required
                                           value="<?= old('course_number') ?>"
                                           placeholder="e.g., IT101 A"
                                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                           onkeypress="return validateAlphanumericSpace(event)"
                                           oninput="validateInput(this)">
                                    <?php if (isset($validation) && $validation->getError('course_number')): ?>
                                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('course_number') ?></p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-500">Unique code for subject or section (e.g., IT101 A). Only letters, numbers, and spaces allowed.</p>
                                    <p id="course_number_error" class="mt-1 text-sm text-red-600 hidden"></p>
                                </div>

                                <!-- Schedule -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="schedule_time_start" class="block text-sm font-medium text-gray-700">Start Time *</label>
                                        <input type="time" id="schedule_time_start" name="schedule_time_start" required
                                               value="<?= old('schedule_time_start') ?>"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <?php if (isset($validation) && $validation->getError('schedule_time_start')): ?>
                                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('schedule_time_start') ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="schedule_time_end" class="block text-sm font-medium text-gray-700">End Time *</label>
                                        <input type="time" id="schedule_time_end" name="schedule_time_end" required
                                               value="<?= old('schedule_time_end') ?>"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <?php if (isset($validation) && $validation->getError('schedule_time_end')): ?>
                                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('schedule_time_end') ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label for="duration" class="block text-sm font-medium text-gray-700">Class Duration (Auto)</label>
                                        <input type="text" id="duration" name="duration" readonly
                                               value="<?= old('duration', '2') ?>"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-primary focus:border-primary">
                                        <?php if (isset($validation) && $validation->getError('duration')): ?>
                                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('duration') ?></p>
                                        <?php endif; ?>
                                        <p class="mt-1 text-xs text-gray-500">Automatically calculated from time range</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mt-4">
                                    <div>
                                        <label for="schedule_date" class="block text-sm font-medium text-gray-700">Schedule Date *</label>
                                        <input type="date" id="schedule_date" name="schedule_date" required
                                               value="<?= old('schedule_date') ?>"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                        <?php if (isset($validation) && $validation->getError('schedule_date')): ?>
                                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('schedule_date') ?></p>
                                        <?php endif; ?>
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
                                <a href="<?= session('role') == 'admin' ? base_url('courses') : base_url('my-courses') ?>" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
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

    <script>
        // Dynamic loading of semesters and terms
        document.addEventListener('DOMContentLoaded', function() {
            const acadYearSelect = document.getElementById('acad_year_id');
            const semesterSelect = document.getElementById('semester_id');

            // Get CSRF token from form
            function getCSRFToken() {
                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                return csrfInput ? csrfInput.value : '<?= csrf_hash() ?>';
            }

            // Load semesters when academic year changes
            if (acadYearSelect) {
                acadYearSelect.addEventListener('change', function() {
                    const acadYearId = this.value;
                    
                    // Reset semester
                    semesterSelect.innerHTML = '<option value="">Loading semesters...</option>';
                    semesterSelect.disabled = true;
                    
                    if (acadYearId) {
                        const formData = new URLSearchParams();
                        formData.append('acad_year_id', acadYearId);
                        formData.append('<?= csrf_token() ?>', getCSRFToken());
                        
                        fetch('<?= base_url('course/get-semesters-by-academic-year') ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData.toString()
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Semesters response:', data);
                            
                            // Update CSRF token if provided
                            if (data.csrf_hash) {
                                const csrfInput = document.querySelector('input[name="<?= csrf_token() ?>"]');
                                if (csrfInput) {
                                    csrfInput.value = data.csrf_hash;
                                }
                            }
                            
                            semesterSelect.innerHTML = '<option value="">Select Semester</option>';
                            semesterSelect.disabled = false;
                            
                            if (data.success && data.semesters && data.semesters.length > 0) {
                                console.log('Found semesters:', data.semesters.length);
                                data.semesters.forEach(semester => {
                                    const option = document.createElement('option');
                                    option.value = semester.id;
                                    option.textContent = semester.name;
                                    semesterSelect.appendChild(option);
                                });
                            } else {
                                semesterSelect.innerHTML = '<option value="">No semesters available</option>';
                                console.log('No semesters found for academic year:', acadYearId, 'Response:', data);
                            }
                        })
                        .catch(error => {
                            console.error('Error loading semesters:', error);
                            semesterSelect.innerHTML = '<option value="">Error loading semesters</option>';
                            semesterSelect.disabled = false;
                        });
                    } else {
                        semesterSelect.innerHTML = '<option value="">Select Academic Year first</option>';
                        semesterSelect.disabled = false;
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
            
            // Set initial value if old input exists
            <?php if(old('teacher_id')): ?>
                const initialTeacher = teachers.find(t => t.id == <?= old('teacher_id') ?>);
                if (initialTeacher) {
                    teacherSearch.value = initialTeacher.name + ' (' + initialTeacher.email + ')';
                    teacherIdInput.value = initialTeacher.id;
                    selectedTeacherName.textContent = initialTeacher.name + ' (' + initialTeacher.email + ')';
                    selectedTeacherDiv.classList.remove('hidden');
                }
            <?php endif; ?>
            
            if (teacherSearch && teacherIdInput && teacherResults) {
                // Show/hide results dropdown
                function showResults() {
                    teacherResults.classList.remove('hidden');
                }
                
                function hideResults() {
                    setTimeout(() => {
                        teacherResults.classList.add('hidden');
                    }, 200);
                }
                
                // Filter and display results
                function filterTeachers(searchTerm) {
                    const term = searchTerm.toLowerCase().trim();
                    teacherResults.innerHTML = '';
                    
                    if (term === '') {
                        teacherResults.classList.add('hidden');
                        return;
                    }
                    
                    const filtered = teachers.filter(teacher => 
                        teacher.name.toLowerCase().includes(term) || 
                        teacher.email.toLowerCase().includes(term)
                    );
                    
                    if (filtered.length === 0) {
                        teacherResults.innerHTML = '<div class="p-3 text-gray-500 text-sm">No teachers found</div>';
                        showResults();
                        return;
                    }
                    
                    filtered.forEach(teacher => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                        div.innerHTML = `<div class="font-medium">${teacher.name}</div><div class="text-xs text-gray-500">${teacher.email}</div>`;
                        div.addEventListener('click', function() {
                            teacherSearch.value = teacher.name + ' (' + teacher.email + ')';
                            teacherIdInput.value = teacher.id;
                            selectedTeacherName.textContent = teacher.name + ' (' + teacher.email + ')';
                            selectedTeacherDiv.classList.remove('hidden');
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
                        selectedTeacherDiv.classList.add('hidden');
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
                        const helperText = durationInput.parentElement.querySelector('.text-xs.text-gray-500');
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
        });
    </script>
</body>
</html>
