<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');          // Homepage
$routes->get('about', 'Home::about');      // About
$routes->get('contact', 'Home::contact');  // Contact


$routes->get('angit', 'Home::angit');      // Extra page
$routes->get('template', 'Home::template');

// Template version (Bootstrap)
$routes->get('/template', 'Template::index');
$routes->get('/template/2', 'Template::page2');
$routes->get('/template/3', 'Template::page3');
