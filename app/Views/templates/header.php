<?php
/**
 * Dynamic Navigation Bar Template
 * Step 5: Role-Based Access Control (RBAC) Navigation
 * This template provides role-specific navigation items accessible from anywhere in the application
 */
$session = \Config\Services::session();
$isLoggedIn = $session->get('isLoggedIn');
$userRole = $session->get('role');
$userName = $session->get('name');
$currentUri = uri_string();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url($isLoggedIn ? 'dashboard' : 'home') ?>">
            <i class="fas fa-graduation-cap me-2"></i>
            <span class="fw-bold">ANA ANA LANGS LMS</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($isLoggedIn): ?>
                    <!-- Dashboard - All logged in users -->
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == 'dashboard' || $currentUri == 'auth/dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                    
                    <?php if ($userRole === 'admin'): ?>
                        <!-- Admin Navigation -->
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'users' ? 'active' : '' ?>" href="<?= base_url('users') ?>">
                                <i class="fas fa-users me-1"></i> Manage Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'courses' ? 'active' : '' ?>" href="<?= base_url('courses') ?>">
                                <i class="fas fa-book me-1"></i> Manage Courses
                            </a>
                        </li>
                    <?php elseif ($userRole === 'teacher'): ?>
                        <!-- Teacher Navigation -->
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'my-courses' ? 'active' : '' ?>" href="<?= base_url('my-courses') ?>">
                                <i class="fas fa-book-reader me-1"></i> My Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'create-course' ? 'active' : '' ?>" href="<?= base_url('create-course') ?>">
                                <i class="fas fa-plus-circle me-1"></i> Create Course
                            </a>
                        </li>
                    <?php elseif ($userRole === 'student'): ?>
                        <!-- Student Navigation -->
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'courses' ? 'active' : '' ?>" href="<?= base_url('courses') ?>">
                                <i class="fas fa-book-open me-1"></i> Browse Courses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentUri == 'my-courses' ? 'active' : '' ?>" href="<?= base_url('my-courses') ?>">
                                <i class="fas fa-graduation-cap me-1"></i> My Learning
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Common Navigation for All Logged In Users -->
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == 'profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>">
                            <i class="fas fa-user me-1"></i> Profile
                        </a>
                    </li>
                <?php else: ?>
                    <!-- Public Navigation (Not Logged In) -->
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == '' || $currentUri == 'home' ? 'active' : '' ?>" href="<?= base_url() ?>">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">
                            <i class="fas fa-info-circle me-1"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == 'contact' ? 'active' : '' ?>" href="<?= base_url('contact') ?>">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <?php if ($isLoggedIn): ?>
                <!-- User Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($userName ?? 'User') ?>&size=32" class="rounded-circle me-2" width="32" height="32" alt="User">
                            <span><?= esc($userName ?? 'User') ?></span>
                            <span class="badge bg-secondary ms-2"><?= ucfirst(esc($userRole ?? '')) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('settings') ?>"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            <?php else: ?>
                <!-- Login Link for Non-Logged In Users -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentUri == 'login' ? 'active' : '' ?>" href="<?= base_url('login') ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

