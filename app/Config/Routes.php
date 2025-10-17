<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================
// 🏠 Public Pages
// ===============================
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');

// ===============================
// 📢 Announcements (for Students)
// ===============================
$routes->get('/announcements', 'Announcements::index', ['filter' => 'roleauth:student']);

// ===============================
// 🔐 Authentication Routes
// ===============================
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::register');

$routes->get('/login', 'Auth::login');     // Login page
$routes->post('/login', 'Auth::login');    // Login form submission

$routes->get('/logout', 'Auth::logout');

// Optional unified dashboard (if used before)
$routes->get('/dashboard', 'Auth::dashboard');
$routes->get('/auth/dashboard', 'Auth::dashboard');

// Enrollment (from previous labs)
$routes->post('/auth/enroll/(:num)', 'Auth::enroll/$1');

// ===============================
// 🧑‍🏫 Teacher Routes
// ===============================
$routes->get('/teacher/dashboard', 'Teacher::dashboard', ['filter' => 'roleauth:teacher']);

// ===============================
// 👨‍💼 Admin Routes
// ===============================
$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'roleauth:admin']);
