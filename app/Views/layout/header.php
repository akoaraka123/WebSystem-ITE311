<?php $role = session()->get('role'); ?>
<ul>
    <?php if($role == 'admin'): ?>
        <li><a href="<?= site_url('admin/dashboard') ?>">Admin Dashboard</a></li>
    <?php elseif($role == 'teacher'): ?>
        <li><a href="<?= site_url('teacher/dashboard') ?>">Teacher Dashboard</a></li>
    <?php elseif($role == 'student'): ?>
        <li><a href="<?= site_url('student/dashboard') ?>">Student Dashboard</a></li>
    <?php endif; ?>
    <li><a href="<?= site_url('auth/logout') ?>">Logout</a></li>
</ul>
