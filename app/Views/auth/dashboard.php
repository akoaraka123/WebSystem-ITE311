<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fb; margin:0; padding:0; }
        .container { max-width: 800px; margin: 60px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px #ccc; }
        h2 { color: #333; margin-bottom: 15px; }
        p { margin: 8px 0; }
        ul { margin-left: 20px; }
        .flash { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .flash-success { background: #d4edda; color: #155724; }
        .btn-logout {
            display: inline-block;
            padding: 8px 15px;
            background-color: #dc3545;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
        }
        .btn-enroll {
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-enroll:hover { background: #0056b3; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>


    <?= view('templates/header') ?>

    <div class="container">
        <h2>Welcome, <?= esc($user['name']) ?> 🎉</h2>

        <?php if (!empty($flash)): ?>
            <div class="flash flash-success"><?= esc($flash) ?></div>
        <?php endif; ?>

        <p><strong>User ID:</strong> <?= esc($user['id']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>

        <hr>

        <!-- === ADMIN DASHBOARD === -->
        <?php if ($user['role'] === 'admin'): ?>
            <h3>Admin Overview 📊</h3>
            <p><strong>Total Users:</strong> <?= esc($totalUsers ?? 0) ?></p>
            <p><strong>Total Courses:</strong> <?= esc($totalCourses ?? 0) ?></p>

        <!-- === TEACHER DASHBOARD === -->
        <?php elseif ($user['role'] === 'teacher'): ?>
            <h3>My Courses 🎓</h3>
            <?php if (!empty($myCourses)): ?>
                <ul>
                    <?php foreach($myCourses as $course): ?>
                        <li>
                            <strong><?= esc($course['course_name'] ?? $course['title'] ?? 'Untitled') ?></strong>
                            <?= !empty($course['description']) ? '— ' . esc($course['description']) : '' ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You don’t have any courses yet.</p>
            <?php endif; ?>

<!-- === STUDENT DASHBOARD === -->
<?php elseif ($user['role'] === 'student'): ?>
    <h3>My Enrolled Courses ✅</h3>
    <ul id="enrolledList">
        <?php if (!empty($enrolled)): ?>
            <?php foreach($enrolled as $course): ?>
                <li id="enrolled-<?= esc($course['id']) ?>">
                    <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                    <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p id="noEnrolled">You are not enrolled in any course yet.</p>
        <?php endif; ?>
    </ul>

    <h3 style="margin-top:25px;">Available Courses 📚</h3>
    <ul id="availableList">
        <?php if (!empty($available)): ?>
            <?php foreach($available as $course): ?>
                <li id="available-<?= esc($course['id']) ?>">
                    <strong><?= esc($course['title'] ?? 'Untitled Course') ?></strong>
                    <?= !empty($course['description']) ? ' — ' . esc($course['description']) : '' ?>
                    <button class="btn-enroll" data-course-id="<?= esc($course['id']) ?>">Enroll</button>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <p>All available courses have been enrolled. 🎉</p>
        <?php endif; ?>
    </ul>

    <!-- Success alert -->
    <div id="enrollMessage" style="display:none; padding:10px; margin:10px 0; border-radius:5px; background:#d4edda; color:#155724;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-enroll').click(function(e) {
                e.preventDefault();
                let courseID = $(this).data('course-id');
                let button = $(this);

                $.post("<?= base_url('auth/enroll') ?>/" + courseID, {}, function(response) {
                    // Show success message
                    $('#enrollMessage').text(response.message).fadeIn().delay(2000).fadeOut();

                    // Move course from available to enrolled
                    let courseItem = $('#available-' + courseID);
                    $('#enrolledList').append(courseItem.clone().attr('id', 'enrolled-' + courseID));
                    courseItem.remove();

                    // Remove "No enrolled courses" text if present
                    $('#noEnrolled').remove();
                }, 'json').fail(function() {
                    alert('Enrollment failed. Please try again.');
                });
            });
        });
    </script>
<?php endif; ?>



</body>
</html>
