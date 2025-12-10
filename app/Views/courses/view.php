<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($course['title'] ?? 'Course Details') ?> - LMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        body.student-shell {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        body.student-shell .course-highlight {
            border: 3px solid #333;
            border-radius: 3px;
            box-shadow: 3px 3px 8px rgba(0,0,0,0.1);
        }

        body.student-shell .info-card {
            border: 2px solid #999;
            background: #fff;
            border-radius: 3px;
        }

        body.student-shell .materials-card li {
            background: #fff;
            border: 2px solid #999;
            border-radius: 3px;
            padding: 10px;
        }

        body.student-shell .btn {
            border: 2px solid;
            font-weight: bold;
            border-radius: 3px;
        }
    </style>
</head>
<body class="text-gray-800 <?= session('role') === 'student' ? 'student-shell' : '' ?>">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <a href="<?= base_url('courses') ?>" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Courses
            </a>
            <?php if (!empty($user['role']) && in_array($user['role'], ['admin', 'teacher'])): ?>
                <div class="space-x-3">
                    <a href="<?= base_url('course/' . ($course['id'] ?? 0)) ?>" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                        <i class="fas fa-eye mr-1"></i> View Public
                    </a>
                    <?php if ($user['role'] === 'teacher' && ($course['teacher_id'] ?? null) == ($user['userID'] ?? null)): ?>
                        <a href="<?= base_url('edit-course/' . ($course['id'] ?? 0)) ?>" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">
                            <i class="fas fa-edit mr-1"></i> Edit Course
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden course-highlight">
            <div class="p-8 md:p-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm uppercase tracking-widest text-blue-500 font-semibold mb-2">Course Overview</p>
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900"><?= esc($course['title'] ?? 'Untitled Course') ?></h1>
                    </div>
                    <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                        Active
                    </span>
                </div>

                <?php if (!empty($course['description'])): ?>
                    <p class="text-gray-600 leading-relaxed mb-8 whitespace-pre-line"><?= esc($course['description']) ?></p>
                <?php else: ?>
                    <p class="text-gray-500 italic mb-8">This course does not have a description yet.</p>
                <?php endif; ?>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (!empty($course['course_number'])): ?>
                    <div class="bg-indigo-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-indigo-400 font-semibold">Course Code</p>
                                <p class="text-lg font-semibold text-gray-800"><?= esc($course['course_number']) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Unique identifier for this course section.</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($course['acad_year_name'])): ?>
                    <div class="bg-green-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-green-400 font-semibold">Academic Year</p>
                                <p class="text-lg font-semibold text-gray-800"><?= esc($course['acad_year_name']) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">The academic year for this course.</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($course['semester_name'])): ?>
                    <div class="bg-yellow-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-yellow-400 font-semibold">Semester</p>
                                <p class="text-lg font-semibold text-gray-800"><?= esc($course['semester_name']) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">The semester period for this course.</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($course['term_name'])): ?>
                    <div class="bg-pink-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600">
                                <i class="fas fa-bookmark"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-pink-400 font-semibold">Term</p>
                                <p class="text-lg font-semibold text-gray-800"><?= esc($course['term_name']) ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">The grading term for this course.</p>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $startTime = $course['schedule_time_start'] ?? $course['schedule_time'] ?? '';
                    $endTime = $course['schedule_time_end'] ?? '';
                    if ($startTime || !empty($course['schedule_date']) || !empty($course['duration'])): 
                    ?>
                    <div class="bg-teal-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-teal-400 font-semibold">Schedule</p>
                                <p class="text-lg font-semibold text-gray-800">
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
                                    <?php 
                                    // Calculate exact duration from start and end times
                                    if ($startTime && $endTime): 
                                        $startTimestamp = strtotime($startTime);
                                        $endTimestamp = strtotime($endTime);
                                        $diffMinutes = round(($endTimestamp - $startTimestamp) / 60);
                                        $hours = floor($diffMinutes / 60);
                                        $minutes = $diffMinutes % 60;
                                        ?>
                                        <?php if ($startTime): ?><br><?php endif; ?>
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
                                    <?php elseif (!empty($course['duration'])): ?>
                                        <?php if ($startTime): ?><br><?php endif; ?>
                                        <strong>Duration:</strong> <?= esc($course['duration']) ?> <?= $course['duration'] == 1 ? 'Hour' : 'Hours' ?>
                                    <?php endif; ?>
                                    <?php if (!empty($course['schedule_date'])): ?>
                                        <?php if ($startTime || !empty($course['duration'])): ?><br><?php endif; ?>
                                        <?= date('M d, Y', strtotime($course['schedule_date'])) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Class schedule, duration, and date.</p>
                    </div>
                    <?php endif; ?>

                    <div class="bg-blue-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-blue-400 font-semibold">Instructor ID</p>
                                <p class="text-lg font-semibold text-gray-800"><?= esc($course['teacher_id'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">This identifier references the teacher assigned to the course.</p>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-5 info-card">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs uppercase text-purple-400 font-semibold">Created On</p>
                                <p class="text-lg font-semibold text-gray-800">
                                    <?= esc(!empty($course['created_at']) ? date('M j, Y g:i A', strtotime($course['created_at'])) : 'Not set') ?>
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Keep track of when this course was initially published.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-100 px-8 py-5 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold">Course ID</p>
                    <p class="text-lg font-semibold text-gray-800">#<?= esc($course['id'] ?? '—') ?></p>
                </div>
                <div class="text-sm text-gray-500">
                    Last updated: <?= esc(!empty($course['updated_at']) ? date('M j, Y g:i A', strtotime($course['updated_at'])) : 'No updates yet') ?>
                </div>
                <div class="flex gap-2">
                    <?php if(session('role') == 'admin'): ?>
                        <a href="<?= base_url('edit-course/' . $course['id']) ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md shadow hover:bg-gray-700">
                            <i class="fas fa-edit mr-2"></i>Edit Course
                        </a>
                        <button type="button" onclick="confirmDeleteCourse(<?= $course['id'] ?>, '<?= htmlspecialchars(addslashes($course['title']), ENT_QUOTES, 'UTF-8') ?>')" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md shadow hover:bg-red-700">
                            <i class="fas fa-trash mr-2"></i>Delete Course
                        </button>
                    <?php endif; ?>
                    <a href="<?= base_url('courses') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700">
                        <i class="fas fa-book-open mr-2"></i>Browse other courses
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden course-highlight">
                <div class="p-8 md:p-10">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-blue-500 font-semibold mb-2">Course Materials</p>
                            <h2 class="text-2xl font-bold text-gray-900">Shared Files</h2>
                        </div>
                        <?php if (!empty($user['role']) && $user['role'] === 'teacher' && ($course['teacher_id'] ?? null) == ($user['userID'] ?? null)): ?>
                            <a href="<?= base_url('materials/upload/' . ($course['id'] ?? 0)) ?>" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold">
                                <i class="fas fa-upload mr-2"></i>Upload Material
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($materials)): ?>
                        <ul class="divide-y divide-gray-200 materials-card">
                            <?php foreach ($materials as $material): ?>
                                <li class="py-4 flex items-center justify-between">
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold text-gray-900">
                                                <?= esc($material['file_name'] ?? 'Untitled Material') ?>
                                            </p>
                                            <p class="text-sm text-gray-500 flex items-center mt-1">
                                                <i class="far fa-clock mr-1"></i>
                                                <?= !empty($material['created_at']) ? date('M j, Y g:i A', strtotime($material['created_at'])) : 'Unknown date' ?>
                                            </p>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('materials/download/' . ($material['id'] ?? 0)) ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 rounded-md border border-blue-200">
                                        <i class="fas fa-download mr-2"></i>Download
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-10">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No materials uploaded yet</h3>
                            <p class="text-gray-500">Once the instructor uploads files for this course, they will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
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

