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

        body.student-shell {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
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
    </style>
</head>
<body class="<?= session('role') === 'student' ? 'student-shell' : '' ?>">
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
                        <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
                            <i class="w-5 h-5 mr-3 fas fa-book"></i>
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
                        <a href="<?= base_url('courses') ?>" class="sidebar-item flex items-center px-4 py-3 text-sm font-medium text-white bg-primary rounded-lg">
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
                        <h1 class="ml-2 text-xl font-semibold text-gray-800">
                            <?= session('role') == 'admin' ? 'Manage Courses' : 'Browse Courses' ?>
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

            <!-- Courses Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="max-w-7xl mx-auto">
                    <!-- Course Stats -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book text-primary text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Courses</p>
                                    <p class="text-2xl font-bold text-gray-900"><?= count($courses) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-secondary text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                                    <p class="text-2xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher text-yellow-500 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Teachers</p>
                                    <p class="text-2xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chart-line text-green-500 text-2xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500">Active</p>
                                    <p class="text-2xl font-bold text-gray-900"><?= count($courses) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Interface -->
                    <div class="bg-white rounded-lg shadow p-6 mb-8 search-panel">
                        <?php if(session('role') === 'student'): ?>
                            <h6 class="mb-1">Quick search</h6>
                        <?php endif; ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form id="searchForm" class="d-flex" method="get" action="<?= base_url('courses/search') ?>">
                                    <div class="input-group">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search courses..." name="search_term" value="<?= esc($searchTerm ?? '') ?>">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="bi bi-search me-2"></i> Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Type to filter instantly or submit to search the database without reloading the page.</p>
                    </div>

                    <!-- Courses List -->
                    <div id="coursesContainer" class="row g-4">
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <div class="col-md-4 mb-4" data-course-item>
                                    <div class="card course-card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0"><?= esc($course['title'] ?? 'Untitled Course') ?></h5>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="card-text flex-grow-1"><?= esc($course['description'] ?? 'No description provided.') ?></p>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-user-tie me-2"></i>
                                                <span>Teacher ID: <?= esc($course['teacher_id'] ?? 'N/A') ?></span>
                                            </div>
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
                                                        <a href="<?= base_url('edit-course/' . $course['id']) ?>" class="btn btn-outline-secondary">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
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
                            const teacherId = course.teacher_id ? course.teacher_id : 'N/A';
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
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-user-tie me-2"></i>
                                                <span>Teacher ID: ${teacherId}</span>
                                            </div>
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
</body>
</html>
