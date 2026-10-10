<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */


 // Authentication: login is public; logout requires login.
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->post('logout', 'Auth::logout', ['filter' => 'auth']);

// Products
$routes->get('products', 'Products::index', ['filter' => 'auth']);
$routes->get('products/create', 'Products::create', ['filter' => 'auth']);
$routes->get('products/edit/(:num)', 'Products::edit/$1', ['filter' => 'auth']);
$routes->post('products/store', 'Products::store', ['filter' => 'auth']);
$routes->post('products/update/(:num)', 'Products::update/$1', ['filter' => 'auth']);
$routes->post('products/archive/(:num)', 'Products::archive/$1', ['filter' => 'auth']);

// Customers
$routes->get('customers', 'Customers::index', ['filter' => 'auth']);
$routes->get('customers/create', 'Customers::create', ['filter' => 'auth']);
$routes->post('customers/store', 'Customers::store', ['filter' => 'auth']);
$routes->get('customers/edit/(:num)', 'Customers::edit/$1', ['filter' => 'auth']);
$routes->post('customers/update/(:num)', 'Customers::update/$1', ['filter' => 'auth']);
$routes->post('customers/delete/(:num)', 'Customers::delete/$1', ['filter' => 'auth']);

// Staff
$routes->get('users', 'Users::index', ['filter' => 'auth']);
$routes->get('users/create', 'Users::create', ['filter' => 'auth']);
$routes->post('users/store', 'Users::store', ['filter' => 'auth']);
$routes->get('users/edit/(:num)', 'Users::edit/$1', ['filter' => 'auth']);
$routes->post('users/update/(:num)', 'Users::update/$1', ['filter' => 'auth']);
$routes->post('users/delete/(:num)', 'Users::delete/$1', ['filter' => 'auth']);


 // Sales Transactions
$routes->get('sales', 'Sales::index', ['filter' => 'auth']);
$routes->get('sales/create', 'Sales::create', ['filter' => 'auth']);
$routes->post('sales/store', 'Sales::store', ['filter' => 'auth']);

