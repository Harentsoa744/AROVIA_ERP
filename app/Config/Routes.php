<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');     // Affichage simple
$routes->post('login', 'AuthController::login'); // Traitement du formulaire
$routes->get('logout', 'AuthController::logout');

$routes->get('/dashboard', 'Home::dashboard');
$routes->get('home', 'Home::index');

$routes->get('pro','ProfilController::index');
$routes->get('profil','ProfilController::index');
$routes->post('profil/update', 'ProfilController::update');


$routes->get('livraisons', 'LivraisonController::index');
$routes->get('livraisons/historique', 'LivraisonController::historique');
$routes->get('livraisons/create', 'LivraisonController::create');
$routes->post('livraisons/store', 'LivraisonController::store');
$routes->get('livraisons/status/(:num)/(:any)', 'LivraisonController::updateStatut/$1/$2');
$routes->get('livraisons/ajax', 'LivraisonController::ajaxList');
$routes->get('livreurs', 'LivreurController::index');
$routes->post('livreurs/store', 'LivreurController::store');
$routes->get('livreurs/edit/(:num)', 'LivreurController::edit/$1');
$routes->post('livreurs/update/(:num)', 'LivreurController::update/$1');



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

// Supermarchés
$routes->get('supermarches', 'Supermarches::index');
$routes->get('supermarches/new', 'Supermarches::new');
$routes->post('supermarches', 'Supermarches::create');
$routes->get('supermarches/(:num)/edit', 'Supermarches::edit/$1');
$routes->post('supermarches/(:num)', 'Supermarches::update/$1');
$routes->get('supermarches/(:num)/delete', 'Supermarches::delete/$1');

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
$routes->get('statistiques/vente', 'StatistiquesVente::index');
$routes->get('valeur-stock', 'ValeurStock::index');
$routes->get('valeur-stock/export', 'ValeurStock::export');
$routes->get('valeur-stock/export-pdf', 'ValeurStock::exportPdf');

// Configuration
$routes->get('configuration', 'Configuration::index');
$routes->post('configuration', 'Configuration::update');

//Arinala

$routes->get('/entreprise', 'EntrepriseController::index');
$routes->get('/entreprise/ajout', 'EntrepriseController::ajout');
$routes->post('/entreprise/save', 'EntrepriseController::save');
$routes->get('/cont', 'ContratController::liste');
$routes->get('/contrat', 'ContratController::index');
$routes->get('/contrat/ajout', 'ContratController::ajout');
$routes->post('/contrat/save', 'ContratController::save');
$routes->get('/contrat/detail/(:num)', 'ContratController::detail/$1');
$routes->get('/contrat/pdf/(:num)', 'ContratController::pdf/$1');
$routes->get('/contrat/recherche', 'ContratController::recherche');
$routes->get('/entreprise/modifier/(:num)', 'EntrepriseController::modifier/$1');
$routes->post('/entreprise/update/(:num)', 'EntrepriseController::update/$1');
$routes->post('/entreprise/supprimer/(:num)', 'EntrepriseController::supprimer/$1');
$routes->get('/contrat/modifier/(:num)', 'ContratController::modifier/$1');
$routes->post('/contrat/update/(:num)', 'ContratController::update/$1');


// -------------------------------------------------------
// Yohan
// -------------------------------------------------------
$routes->get('finances', 'Finances::index');
$routes->post('finances/store', 'Finances::store');
$routes->get('finances/delete/(:num)', 'Finances::delete/$1');
$routes->get('finances/tresorerie', 'Finances::tresorerie');
$routes->get('finances/rapport', 'Finances::rapport');

$routes->get('statistiques/vente', 'StatistiquesVente::index');
$routes->group('statistiques', function($routes) {
    $routes->get('encaissements', 'StatistiquesVente::encaissements');
    $routes->get('decaissements', 'StatistiquesVente::decaissements');
    $routes->get('api/graphique', 'StatistiquesVente::apiGraphique');
});

// -------------------------------------------------------
// Planning
// -------------------------------------------------------
$routes->get('emploi-temps', 'PlanningController::index');
$routes->get('planning/calendrier', 'PlanningController::index');
$routes->get('planning/events', 'PlanningController::events');
$routes->get('planning/liste', 'PlanningController::liste');
$routes->get('planning/ajouter', 'PlanningController::ajouter');
$routes->post('planning/save', 'PlanningController::save');
$routes->get('planning/details/(:num)', 'PlanningController::details/$1');
$routes->get('planning/modifier/(:num)', 'PlanningController::modifier/$1');
$routes->post('planning/update/(:num)', 'PlanningController::update/$1');
$routes->get('planning/delete/(:num)', 'PlanningController::delete/$1');