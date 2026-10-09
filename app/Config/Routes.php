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

    $routes->get('categories', 'Admin\CatalogTerms::categories');
    $routes->get('categories/new', 'Admin\CatalogTerms::newCategory');
    $routes->post('categories', 'Admin\CatalogTerms::storeCategory');
    $routes->get('categories/(:num)/edit', 'Admin\CatalogTerms::editCategory/$1');
    $routes->post('categories/(:num)', 'Admin\CatalogTerms::updateCategory/$1');
    $routes->post('categories/(:num)/delete', 'Admin\CatalogTerms::deleteCategory/$1');

    $routes->get('brands', 'Admin\CatalogTerms::brands');
    $routes->get('brands/new', 'Admin\CatalogTerms::newBrand');
    $routes->post('brands', 'Admin\CatalogTerms::storeBrand');
    $routes->get('brands/(:num)/edit', 'Admin\CatalogTerms::editBrand/$1');
    $routes->post('brands/(:num)', 'Admin\CatalogTerms::updateBrand/$1');
    $routes->post('brands/(:num)/delete', 'Admin\CatalogTerms::deleteBrand/$1');

    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile', 'Admin\Profile::updateDetails');
    $routes->post('profile/password', 'Admin\Profile::changePassword');
});
