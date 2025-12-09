<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================================
// PUBLIC PAGES
// ========================================
$routes->get('/', 'Pages::home');
$routes->get('home', 'Pages::home');
$routes->get('about', 'Pages::about');
$routes->get('contact', 'Pages::contact');

// ========================================
// AUTHENTICATION
// ========================================
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login', ['filter' => 'ratelimit']);
$routes->get('logout', 'Auth::logout');
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::forgotPassword');
$routes->get('reset-password/(:segment)', 'Auth::resetPassword/$1');
$routes->post('reset-password', 'Auth::resetPassword');

// ========================================
// DASHBOARD
// ========================================
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('auth/dashboard', 'Auth::dashboard');

// ========================================
// USER MANAGEMENT
// ========================================
$routes->get('users', 'User::index');
$routes->post('users/create', 'User::create');
$routes->post('users/update', 'User::update');
$routes->post('users/delete', 'User::delete');
$routes->post('users/recover', 'User::recoverAccount');
$routes->get('profile', 'User::profile');
$routes->post('profile', 'User::updateProfile');
$routes->get('settings', 'User::settings');
$routes->post('settings', 'User::updateSettings');

// ========================================
// COURSE MANAGEMENT
// ========================================
$routes->get('courses', 'Course::index');
$routes->get('my-courses', 'Course::myCourses');
$routes->get('create-course', 'Course::create');
$routes->post('create-course', 'Course::store');
$routes->get('edit-course/(:num)', 'Course::edit/$1');
$routes->post('edit-course/(:num)', 'Course::update/$1');
$routes->get('course/(:num)', 'Course::view/$1');
$routes->post('course/delete/(:num)', 'Course::delete/$1');
$routes->get('courses/search', 'Course::search');
$routes->post('courses/search', 'Course::search');
$routes->get('course/(:num)/students/available', 'Course::getAvailableStudents/$1');
$routes->get('course/(:num)/enrollments', 'Course::getEnrollmentDetails/$1');
$routes->get('course/(:num)/students', 'Course::getEnrolledStudents/$1');
$routes->post('course/add-student', 'Course::addStudent');
$routes->post('course/accept-enrollment', 'Course::acceptEnrollment');
$routes->post('course/reject-enrollment', 'Course::rejectEnrollment');
$routes->get('courses/getAllStudents', 'Course::getAllStudents');
$routes->get('courses/getAllTeachers', 'Course::getAllTeachers');
$routes->post('courses/adminEnrollStudent', 'Course::adminEnrollStudent');
$routes->post('courses/assignTeacher', 'Course::assignTeacher');

// ========================================
// ENROLLMENT
// ========================================
$routes->post('auth/enroll/(:num)', 'Auth::enroll/$1');
$routes->post('course/enroll', 'Auth::enroll');
$routes->post('auth/unenroll/(:num)', 'Auth::unenroll/$1');

// ========================================
// MATERIALS MANAGEMENT
// ========================================
$routes->post('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/upload_ajax/(:num)', 'Materials::upload_ajax/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->post('materials/delete/(:num)', 'Materials::delete/$1');

// ========================================
// NOTIFICATIONS
// ========================================
$routes->get('notifications', 'Notifications::index');
$routes->post('notifications/mark_read', 'Notifications::markRead');
$routes->post('notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');
$routes->post('notifications/mark-read/(:num)', 'Notifications::mark_as_read/$1');
$routes->get('notifications/resolve/(:num)', 'Notifications::resolve/$1');
$routes->post('notifications/add', 'Notifications::add');

// ========================================
// SCHOOL SETUP (Admin Only)
// ========================================
$routes->get('school-setup', 'SchoolSetup::index');
$routes->post('school-setup/saveSettings', 'SchoolSetup::saveSettings');
$routes->post('school-setup/saveProgram', 'SchoolSetup::saveProgram');
$routes->post('school-setup/deleteProgram/(:num)', 'SchoolSetup::deleteProgram/$1');
$routes->get('school-setup/getProgram/(:num)', 'SchoolSetup::getProgram/$1');

// ========================================
// SEEDER (for testing)
// ========================================
$routes->get('seed', 'Seed::index');

// ========================================
// OPTIONAL: 404 Override
// ========================================
// $routes->set404Override('Errors::show404');
