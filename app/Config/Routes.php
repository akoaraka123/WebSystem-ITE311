<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Pages
$routes->get('/', 'Home::index');
$routes->get('/announcements', 'Announcement::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('login', 'Auth::login'); // Page ng login form
$routes->post('login', 'Auth::login'); // Action kapag nag-submit
$routes->get('logout', 'Auth::logout');
$routes->get('/dashboard', 'Auth::dashboard');

$routes->get('auth/dashboard', 'Auth::dashboard');

$routes->post('auth/enroll/(:num)', 'Auth::enroll/$1');

