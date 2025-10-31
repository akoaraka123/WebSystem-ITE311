<!-- app/Views/templates/header.php -->
<nav style="background:#4CAF50; padding:10px; position:relative;">
    <ul style="list-style:none; margin:0; padding:0; display:flex; justify-content:center; align-items:center;">
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

        <!-- Notifications Bell -->
        <?php if(session('isLoggedIn')): ?>
        <li style="margin:0 15px; position:relative;">
            <a href="#" id="notifBell" style="color:white; text-decoration:none; position:relative;">
                🔔
                <span id="notifBadge" class="badge bg-danger rounded-pill" style="position:absolute; top:-6px; right:-12px; display:none;">0</span>
            </a>
            <!-- Dropdown -->
            <div id="notifDropdown" style="display:none; position:absolute; right:0; top:28px; background:#fff; color:#333; min-width:260px; box-shadow:0 2px 8px rgba(0,0,0,0.2); border-radius:6px; z-index:9999;">
                <div style="padding:8px 12px; border-bottom:1px solid #eee; font-weight:bold;">Notifications</div>
                <div id="notifList" style="max-height:260px; overflow:auto;"></div>
                <div style="padding:8px 12px; border-top:1px solid #eee; font-size:12px; color:#666;">Click the bell to mark all as read</div>
            </div>
        </li>
        <?php endif; ?>

        <li style="margin:0 15px;"><a href="<?= base_url('logout') ?>" style="color:white; text-decoration:none;">Logout</a></li>
    </ul>
</nav>
