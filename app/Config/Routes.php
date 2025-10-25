<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ===============================
// PUBLIC PAGES
// ===============================
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// ===============================
// AUTHENTICATION
// ===============================
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');

$routes->get('auth/dashboard', 'Auth::dashboard');

$routes->post('auth/enroll/(:num)', 'Auth::enroll/$1');

// ===============================
// MATERIALS
// ===============================
$routes->get('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->post('/admin/course/(:num)/upload', 'Materials::upload/$1');
$routes->get('/materials/download/(:num)', 'Materials::download/$1');
$routes->get('/materials/delete/(:num)', 'Materials::delete/$1');
$routes->get('/course/(:num)/materials', 'Materials::listByCourse/$1');

// (Optional duplicate routes kung gusto mo accessible din sa /materials/upload/... )
$routes->get('materials/upload/(:num)', 'Materials::upload/$1');
$routes->post('materials/upload/(:num)', 'Materials::upload/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

$routes->get('materials/testUpload', 'Materials::testUpload');
$routes->post('materials/testUpload', 'Materials::testUpload');

