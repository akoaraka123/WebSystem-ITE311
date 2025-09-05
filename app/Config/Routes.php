<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('home', 'Home::index'); // Optional pero safe
$routes->get('/', 'Home::index');    // Default route

