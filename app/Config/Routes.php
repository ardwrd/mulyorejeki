<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('products', 'Catalog::index');
$routes->get('products/(:segment)', 'Catalog::show/$1');
$routes->get('catalog', 'Catalog::index');

$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::authenticate');
$routes->post('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminAuth'], static function (RouteCollection $routes): void {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/new', 'Admin\Products::create');
    $routes->post('products', 'Admin\Products::store');
    $routes->get('products/(:num)/edit', 'Admin\Products::edit/$1');
    $routes->post('products/(:num)', 'Admin\Products::update/$1');
    $routes->post('products/(:num)/delete', 'Admin\Products::delete/$1');
    $routes->post('products/(:num)/images/(:num)/delete', 'Admin\Products::deleteImage/$1/$2');
    $routes->post('products/(:num)/images/(:num)/primary', 'Admin\Products::setPrimaryImage/$1/$2');
});
