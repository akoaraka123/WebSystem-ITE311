<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================
//  🌐 Public Pages
// ===============================
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// ===============================
//  🔐 Authentication Routes
// ===============================
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');

$routes->get('/login', 'Auth::login');     // Login page
$routes->post('/login', 'Auth::login');    // Login form submission

$routes->get('/logout', 'Auth::logout');


$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/auth/dashboard', 'Auth::dashboard');


$routes->post('/auth/enroll/(:num)', 'Auth::enroll/$1');

// ===============================
//  🧑‍🏫 Teacher Routes (Protected)
// ===============================
$routes->group('teacher', ['filter' => 'roleauth:teacher'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// ===============================
//  👨‍💼 Admin Routes (Protected)
// ===============================
$routes->group('admin', ['filter' => 'roleauth:admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
});

// ===============================
//  🎓 Student Routes (Protected)
// ===============================
$routes->group('student', ['filter' => 'roleauth:student'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// ===============================
//  📢 Announcements (Accessible by Students Only)
// ===============================
$routes->get('/announcements', 'Announcement::index',);

