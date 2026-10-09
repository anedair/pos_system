<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('products', 'Products::index');
$routes->get('products/create', 'Products::create');
$routes->post('products/store', 'Products::store');
$routes->get('products/edit/(:num)', 'Products::edit/$1');
$routes->post('products/update/(:num)', 'Products::update/$1');
$routes->post('products/archive/(:num)', 'Products::archive/$1');

