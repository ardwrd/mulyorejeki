<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('products', 'Catalog::index');
$routes->get('products/(:segment)', 'Catalog::show/$1');

// Temporary aliases while the static preview is still being reviewed.
$routes->get('catalog', 'Catalog::index');
