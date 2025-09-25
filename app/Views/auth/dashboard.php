<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fb; margin:0; padding:0; }
        .container { max-width: 700px; margin: 60px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px #ccc; }
        h2 { color: #333; }
        p { margin: 8px 0; }
        .flash { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .flash-success { background: #d4edda; color: #155724; }
        ul { margin-left: 20px; }
    </style>
</head>
<body>

    <!-- Include dynamic navbar -->
    <?= view('templates/header') ?>

    <div class="container">
        <h2>Welcome, <?= esc($user['name']) ?> 🎉</h2>

        <?php if (!empty($flash)): ?>
            <div class="flash flash-success"><?= esc($flash) ?></div>
        <?php endif; ?>

        <p><strong>User ID:</strong> <?= esc($user['id']) ?></p>
        <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
        <p><strong>Role:</strong> <?= esc($user['role']) ?></p>

        <!-- Role-based content -->
        <?php if ($user['role'] === 'admin'): ?>
            <h3 style="margin-top:20px;">Admin Stats:</h3>
            <p>Total Users: <?= $totalUsers ?? 0 ?></p>
            <p>Total Courses: <?= $totalCourses ?? 0 ?></p>

        <?php elseif ($user['role'] === 'teacher'): ?>
            <h3 style="margin-top:20px;">My Courses:</h3>
            <ul>
                <?php foreach($myCourses ?? [] as $course): ?>
                    <li><?= esc($course['title']) ?></li>
                <?php endforeach; ?>
            </ul>

        <?php elseif ($user['role'] === 'student'): ?>
            <h3 style="margin-top:20px;">Enrolled Courses:</h3>
            <ul>
                <?php foreach($enrolledCourses ?? [] as $course): ?>
                    <li><?= esc($course['course_name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p style="margin-top:20px;">You are now logged in. ✅</p>
    </div>

</body>
</html>
