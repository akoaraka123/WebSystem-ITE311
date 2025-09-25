<!-- app/Views/templates/header.php -->
<nav style="background:#4CAF50; padding:10px;">
    <ul style="list-style:none; margin:0; padding:0; display:flex; justify-content:center;">
        <li style="margin:0 15px;"><a href="<?= base_url('dashboard') ?>" style="color:white; text-decoration:none;">Dashboard</a></li>

        <?php if(session('role') == 'admin'): ?>
            <li style="margin:0 15px;"><a href="<?= base_url('users') ?>" style="color:white; text-decoration:none;">Manage Users</a></li>
            <li style="margin:0 15px;"><a href="<?= base_url('courses') ?>" style="color:white; text-decoration:none;">Manage Courses</a></li>

        <?php elseif(session('role') == 'teacher'): ?>
            <li style="margin:0 15px;"><a href="<?= base_url('my-courses') ?>" style="color:white; text-decoration:none;">My Courses</a></li>
            <li style="margin:0 15px;"><a href="<?= base_url('create-course') ?>" style="color:white; text-decoration:none;">Create Course</a></li>

        <?php elseif(session('role') == 'student'): ?>
            <li style="margin:0 15px;"><a href="<?= base_url('my-enrollments') ?>" style="color:white; text-decoration:none;">My Courses</a></li>
        <?php endif; ?>

        <li style="margin:0 15px;"><a href="<?= base_url('logout') ?>" style="color:white; text-decoration:none;">Logout</a></li>
    </ul>
</nav>
