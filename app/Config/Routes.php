<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================================
// PUBLIC PAGES
// ========================================
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// ========================================
// AUTHENTICATION
// ========================================
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ========================================
// DASHBOARD
// ========================================
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('auth/dashboard', 'Auth::dashboard');

// ========================================
// ENROLLMENT
// ========================================
$routes->post('auth/enroll/(:num)', 'Auth::enroll/$1');

// ========================================
// MATERIALS MANAGEMENT
// ========================================

// Upload materials (Admin)
$routes->get('admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('admin/course/(:num)/upload', 'Materials::upload/$1');

// Alternate route (optional)
$routes->get('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/upload/(:num)', 'Materials::upload/$1');

// AJAX upload endpoint
$routes->post('materials/upload_ajax/(:num)', 'Materials::upload_ajax/$1');

// Download material (for enrolled students)
$routes->get('materials/download/(:num)', 'Materials::download/$1');

// Delete material (Admin)
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

// Fetch materials list by course (AJAX / dashboard use)
$routes->get('course/(:num)/materials', 'Materials::getMaterials/$1');

// Test upload (for debugging / permission check)
$routes->match(['get', 'post'], 'materials/testUpload', 'Materials::testUpload');

// Enrollment (AJAX)
$routes->post('course/enroll', 'Course::enroll');

// ========================================
// OPTIONAL: 404 Override
// ========================================
// $routes->set404Override('Errors::show404');
