<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/employes/ajax', 'EmployeController::ajaxList');

$routes->get('employes/index', 'EmployeController::index');

$routes->get('employes', 'EmployeController::index');
$routes->get('employes/index', 'EmployeController::index');

$routes->get('employes/create', 'EmployeController::create');
$routes->post('employes/store', 'EmployeController::store');

$routes->get('employes/show/(:num)', 'EmployeController::show/$1');

$routes->get('employes/edit/(:num)', 'EmployeController::edit/$1');
$routes->post('employes/update/(:num)', 'EmployeController::update/$1');

$routes->get('employes/delete/(:num)', 'EmployeController::fire/$1');

$routes->get('fournisseurs', 'Fournisseurs::index');
$routes->get('fournisseurs/new', 'Fournisseurs::new');
$routes->post('fournisseurs', 'Fournisseurs::create');
$routes->get('fournisseurs/(:num)/edit', 'Fournisseurs::edit/$1');
$routes->post('fournisseurs/(:num)', 'Fournisseurs::update/$1');
$routes->get('fournisseurs/(:num)/delete', 'Fournisseurs::delete/$1');
$routes->get('entrees-matiere-premiere', 'EntreesMatierePremiere::index');
$routes->get('entrees-matiere-premiere/new', 'EntreesMatierePremiere::new');
$routes->post('entrees-matiere-premiere', 'EntreesMatierePremiere::create');
$routes->get('transformations', 'Transformations::index');
$routes->get('transformations/new', 'Transformations::new');
$routes->post('transformations', 'Transformations::create');
$routes->get('sorties', 'Sorties::index');
$routes->get('sorties/new', 'Sorties::new');
$routes->post('sorties', 'Sorties::create');
$routes->get('statistiques', 'Statistiques::index');
$routes->get('valeur-stock', 'ValeurStock::index');