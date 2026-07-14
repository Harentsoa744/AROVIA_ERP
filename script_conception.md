## 1. Authentification

### Tables utiles

```sql
CREATE TABLE roles (
    id  SERIAL PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE utilisateurs (
    id            SERIAL PRIMARY KEY,
    nom           VARCHAR(100) NOT NULL,
    prenom        VARCHAR(100),
    email         VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe  TEXT NOT NULL,
    role_id       INTEGER REFERENCES roles(id),
    actif         BOOLEAN   DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT NOW()
);
```

### Routes.php

```php
$routes->get('/', 'AuthController::index');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('home', 'Home::index');
```

### Fichiers à créer

- **Controller**: `app/Controllers/AuthController.php`
  - Méthodes: `index()`, `login()`, `logout()`
  
- **Model**: `app/Models/UtilisateurModel.php`
  - Table: `utilisateurs`
  - Méthodes: `login($email, $password)`, `findUserById($id)`
  
- **View**: `app/Views/auth/login.php`
  - Formulaire avec champs: `email`, `password`

### Étapes de développement

1. Créer les tables SQL
2. Créer le Model UtilisateurModel avec méthode login
3. Créer le Controller AuthController
4. Créer la View login.php
5. Configurer les routes
6. Tester: login → session → redirection vers home

---

## 2. Dashboard (Accueil)

### Tables utiles

```sql
-- Réutilise: stock_matiere_premiere, stock_produit_fini, types_bocaux, fournisseurs, employes
```

### Routes.php

```php
$routes->get('/dashboard', 'Home::dashboard');
$routes->get('home', 'Home::index');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Home.php`
  - Méthodes: `index()`, `dashboard()`
  
- **View**: `app/Views/home.php`
  - Affiche: stock MP, stock PF, alertes, KPIs

### Étapes de développement

1. Créer le Controller Home
2. Récupérer données: stock MP, stock PF, nb fournisseurs, nb employés
3. Calculer alertes: stock < seuil_alerte
4. Créer la View home.php
5. Tester: après login → affichage dashboard

---

## 3. Gestion des Fournisseurs

### Tables utiles

```sql
CREATE TABLE fournisseurs (
    id           SERIAL PRIMARY KEY,
    nom          VARCHAR(150) NOT NULL,
    contact      VARCHAR(150),
    telephone    VARCHAR(50),
    email        VARCHAR(150),
    localisation VARCHAR(150)
);
```

### Routes.php

```php
$routes->get('fournisseurs', 'Fournisseurs::index');
$routes->get('fournisseurs/new', 'Fournisseurs::new');
$routes->post('fournisseurs', 'Fournisseurs::create');
$routes->get('fournisseurs/(:num)/edit', 'Fournisseurs::edit/$1');
$routes->post('fournisseurs/(:num)', 'Fournisseurs::update/$1');
$routes->get('fournisseurs/(:num)/delete', 'Fournisseurs::delete/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Fournisseurs.php`
  - Méthodes: `index()`, `new()`, `create()`, `edit()`, `update()`, `delete()`
  
- **Model**: `app/Models/FournisseurModel.php`
  - Table: `fournisseurs`
  
- **Views**: `app/Views/fournisseurs/index.php`, `new.php`, `edit.php`

### Étapes de développement

1. Créer la table fournisseurs
2. Créer le Model FournisseurModel
3. Créer le Controller avec CRUD complet
4. Créer les Views (liste, formulaire création, formulaire édition)
5. Configurer les routes
6. Tester: création, modification, suppression

---

## 4. Gestion Stock - Matière Première (Miel Brut)

### Tables utiles

```sql
CREATE TABLE stock_matiere_premiere (
    id              SERIAL PRIMARY KEY,
    quantite_litres NUMERIC(10,2) NOT NULL DEFAULT 0,
    valeur_stock    NUMERIC(12,2) NOT NULL DEFAULT 0,
    cump_actuel     NUMERIC(10,2) NOT NULL DEFAULT 0,
    derniere_maj    TIMESTAMP NOT NULL DEFAULT NOW(),
    seuil_alerte    NUMERIC(10,2) DEFAULT 10
);

CREATE TABLE entrees_matiere_premiere (
    id                SERIAL PRIMARY KEY,
    fournisseur_id    INTEGER REFERENCES fournisseurs(id),
    numero_lot        VARCHAR(50) UNIQUE,
    date_entree       TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres   NUMERIC(10,2) NOT NULL,
    prix_unitaire     NUMERIC(10,2) NOT NULL,
    valeur_totale     NUMERIC(12,2) NOT NULL,
    cump_apres_entree NUMERIC(10,2) NOT NULL
);
```

### Routes.php

```php
$routes->get('entrees-matiere-premiere', 'EntreesMatierePremiere::index');
$routes->get('entrees-matiere-premiere/new', 'EntreesMatierePremiere::new');
$routes->post('entrees-matiere-premiere', 'EntreesMatierePremiere::create');
```

### Fichiers à créer

- **Controller**: `app/Controllers/EntreesMatierePremiere.php`
  - Méthodes: `index()`, `new()`, `create()`
  
- **Model**: `app/Models/EntreeMatierePremiereModel.php`
  - Table: `entrees_matiere_premiere`
  
- **Model**: `app/Models/StockMatierePremiereModel.php`
  - Table: `stock_matiere_premiere`
  - Méthode clé: `enregistrerEntree($fournisseurId, $quantite, $prixUnitaire)`
  
- **Views**: `app/Views/entrees_matiere_premiere/index.php`, `new.php`

### Étapes de développement

1. Créer les tables stock_matiere_premiere et entrees_matiere_premiere
2. Créer les Models
3. Implémenter la méthode enregistrerEntree avec calcul CUMP
4. Créer le Controller
5. Créer les Views
6. Tester: entrée → calcul CUMP → mise à jour stock

---

## 5. Production - Transformations (Mise en Bocal)

### Tables utiles

```sql
CREATE TABLE types_bocaux (
    id            SERIAL PRIMARY KEY,
    nom           VARCHAR(20) NOT NULL,
    volume_litres NUMERIC(4,2) NOT NULL,
    cible         VARCHAR(50),
    prix_vente    NUMERIC(10,2)
);

CREATE TABLE transformations (
    id                       SERIAL PRIMARY KEY,
    date_transformation      TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres_utilisee NUMERIC(10,2) NOT NULL,
    cump_applique            NUMERIC(10,2) NOT NULL,
    valeur_sortie            NUMERIC(12,2) NOT NULL,
    fournisseur_id           INTEGER REFERENCES fournisseurs(id),
    date_production          DATE DEFAULT CURRENT_DATE,
    date_limite_vente        DATE,
    duree_conservation_mois  INTEGER DEFAULT 24
);

CREATE TABLE transformations_detail (
    id                SERIAL PRIMARY KEY,
    transformation_id INTEGER REFERENCES transformations(id) ON DELETE CASCADE,
    type_bocal_id     INTEGER REFERENCES types_bocaux(id),
    quantite_produite INTEGER NOT NULL
);

CREATE TABLE stock_produit_fini (
    type_bocal_id       INTEGER PRIMARY KEY REFERENCES types_bocaux(id),
    quantite_disponible INTEGER NOT NULL DEFAULT 0,
    seuil_alerte        INTEGER DEFAULT 20
);
```

### Routes.php

```php
$routes->get('transformations', 'Transformations::index');
$routes->get('transformations/new', 'Transformations::new');
$routes->post('transformations', 'Transformations::create');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Transformations.php`
  - Méthodes: `index()`, `new()`, `create()`
  
- **Model**: `app/Models/TransformationModel.php`
  - Table: `transformations`
  - Méthode clé: `enregistrerTransformation($repartition, $fournisseurId)`
  
- **Model**: `app/Models/TypeBocalModel.php`
  - Table: `types_bocaux`
  
- **Model**: `app/Models/StockProduitFiniModel.php`
  - Table: `stock_produit_fini`
  
- **Views**: `app/Views/transformations/index.php`, `new.php`

### Étapes de développement

1. Créer les tables types_bocaux, transformations, transformations_detail, stock_produit_fini
2. Créer les Models
3. Implémenter enregistrerTransformation (vérifie stock MP, décrémente, incrémente PF)
4. Créer le Controller
5. Créer les Views
6. Tester: transformation → consommation MP → production PF

---

## 6. Gestion des Supermarchés

### Tables utiles

```sql
CREATE TABLE supermarches (
    id          SERIAL PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    contact     VARCHAR(150),
    telephone   VARCHAR(50),
    email       VARCHAR(150),
    localisation VARCHAR(150),
    actif       BOOLEAN DEFAULT TRUE
);
```

### Routes.php

```php
$routes->get('supermarches', 'Supermarches::index');
$routes->get('supermarches/new', 'Supermarches::new');
$routes->post('supermarches', 'Supermarches::create');
$routes->get('supermarches/(:num)/edit', 'Supermarches::edit/$1');
$routes->post('supermarches/(:num)', 'Supermarches::update/$1');
$routes->get('supermarches/(:num)/delete', 'Supermarches::delete/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Supermarches.php`
  - Méthodes: `index()`, `new()`, `create()`, `edit()`, `update()`, `delete()`
  
- **Model**: `app/Models/SupermarcheModel.php`
  - Table: `supermarches`
  
- **Views**: `app/Views/supermarches/index.php`, `new.php`, `edit.php`

### Étapes de développement

1. Créer la table supermarches
2. Créer le Model SupermarcheModel
3. Créer le Controller avec CRUD
4. Créer les Views
5. Configurer les routes
6. Tester: CRUD complet

---

## 7. Commercialisation - Sorties (Ventes)

### Tables utiles

```sql
CREATE TABLE sorties (
    id                  SERIAL PRIMARY KEY,
    date_sortie         TIMESTAMP NOT NULL DEFAULT NOW(),
    supermarche_id      INTEGER REFERENCES supermarches(id),
    type_bocal_id       INTEGER REFERENCES types_bocaux(id),
    quantite            INTEGER NOT NULL,
    prix_vente_unitaire NUMERIC(10,2) NOT NULL DEFAULT 0,
    valeur_totale       NUMERIC(12,2) NOT NULL DEFAULT 0,
    cump_applique       NUMERIC(10,2) DEFAULT 0,
    marge_unitaire      NUMERIC(10,2) DEFAULT 0,
    marge_totale        NUMERIC(12,2) DEFAULT 0,
    motif               VARCHAR(50),
    commentaire         TEXT
);
```

### Routes.php

```php
$routes->get('sorties', 'Sorties::index', ['filter' => 'role:MAGASINIER,COMPTABLE,ADMIN,RESPONSABLE']);
$routes->get('sorties/new', 'Sorties::new', ['filter' => 'role:MAGASINIER,COMPTABLE,ADMIN,RESPONSABLE']);
$routes->post('sorties', 'Sorties::create', ['filter' => 'role:MAGASINIER,COMPTABLE,ADMIN,RESPONSABLE']);
$routes->get('sorties/facture/(:num)', 'Sorties::facture/$1');
$routes->get('sorties/imprimer/(:num)', 'Sorties::imprimer/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Sorties.php`
  - Méthodes: `index()`, `new()`, `create()`, `facture()`, `imprimer()`
  
- **Model**: `app/Models/SortieModel.php`
  - Table: `sorties`
  - Méthode clé: `enregistrerSortie($typeBocalId, $quantite, $supermarcheId, $prixUnitaire)`
  
- **Views**: `app/Views/sorties/index.php`, `new.php`, `facture.php`, `facture_impression.php`

### Étapes de développement

1. Créer la table sorties
2. Créer le Model SortieModel
3. Implémenter enregistrerSortie (vérifie stock PF, décrémente, calcule marge)
4. Créer le Controller
5. Créer les Views
6. Tester: sortie → décrémentation stock PF → facture

---

## 8. Configuration des Seuils d'Alerte

### Tables utiles

```sql
-- Met à jour: stock_matiere_premiere.seuil_alerte, stock_produit_fini.seuil_alerte
```

### Routes.php

```php
$routes->get('configuration', 'Configuration::index');
$routes->post('configuration', 'Configuration::update');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Configuration.php`
  - Méthodes: `index()`, `update()`
  
- **View**: `app/Views/configuration/index.php`

### Étapes de développement

1. Créer le Controller Configuration
2. Créer la View avec formulaire pour modifier les seuils
3. Implémenter update() pour sauvegarder les seuils
4. Tester: modification seuil → alerte dashboard mise à jour

---

## 9. Valeur du Stock

### Tables utiles

```sql
-- Réutilise: stock_matiere_premiere, stock_produit_fini, types_bocaux
```

### Routes.php

```php
$routes->get('valeur-stock', 'ValeurStock::index');
$routes->get('valeur-stock/export', 'ValeurStock::export');
$routes->get('valeur-stock/export-pdf', 'ValeurStock::exportPdf');
```

### Fichiers à créer

- **Controller**: `app/Controllers/ValeurStock.php`
  - Méthodes: `index()`, `export()`, `exportPdf()`
  
- **Views**: `app/Views/valeur_stock/index.php`, `pdf.php`

### Étapes de développement

1. Créer le Controller ValeurStock
2. Implémenter calcul: valeur MP + valeur PF (CUMP × volume)
3. Créer la View index.php
4. Implémenter export CSV et PDF
5. Tester: affichage valeur + exports

---

## 10. Finances

### Tables utiles

```sql
CREATE TABLE finances (
    id             SERIAL PRIMARY KEY,
    type_operation VARCHAR(50) NOT NULL,
    categorie      VARCHAR(100),
    description    TEXT,
    montant        NUMERIC(12,2) NOT NULL,
    date_operation TIMESTAMP DEFAULT NOW(),
    reference      VARCHAR(100)
);
```

### Routes.php

```php
$routes->get('finances', 'Finances::index', ['filter' => 'role:ADMIN,RESPONSABLE,COMPTABLE']);
$routes->post('finances/store', 'Finances::store', ['filter' => 'role:ADMIN,RESPONSABLE,COMPTABLE']);
$routes->get('finances/delete/(:num)', 'Finances::delete/$1', ['filter' => 'role:ADMIN,RESPONSABLE,COMPTABLE']);
$routes->get('finances/tresorerie', 'Finances::tresorerie', ['filter' => 'role:ADMIN,RESPONSABLE,COMPTABLE']);
$routes->get('finances/rapport', 'Finances::rapport', ['filter' => 'role:ADMIN,RESPONSABLE,COMPTABLE']);
```

### Fichiers à créer

- **Controller**: `app/Controllers/Finances.php`
  - Méthodes: `index()`, `store()`, `delete()`, `tresorerie()`, `rapport()`
  
- **Model**: `app/Models/FinanceModel.php`
  - Table: `finances`
  
- **Views**: `app/Views/finances/index.php`, `tresorerie.php`, `rapport.php`

### Étapes de développement

1. Créer la table finances
2. Créer le Model FinanceModel
3. Créer le Controller avec CRUD
4. Créer les Views
5. Tester: enregistrement recettes/dépenses → rapports

---

## 11. Factures

### Tables utiles

```sql
CREATE TABLE clients (
    id          SERIAL PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    type_client VARCHAR(50),
    telephone   VARCHAR(50),
    email       VARCHAR(150),
    adresse     TEXT
);

CREATE TABLE ventes (
    id            SERIAL PRIMARY KEY,
    client_id     INTEGER REFERENCES clients(id),
    date_vente    TIMESTAMP DEFAULT NOW(),
    montant_total NUMERIC(12,2),
    mode_paiement VARCHAR(50),
    statut        VARCHAR(50) DEFAULT 'PAYE'
);

CREATE TABLE vente_details (
    id            SERIAL PRIMARY KEY,
    vente_id      INTEGER REFERENCES ventes(id) ON DELETE CASCADE,
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite      INTEGER NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    total_ligne   NUMERIC(12,2) NOT NULL
);
```

### Routes.php

```php
$routes->group('factures', function ($routes) {
    $routes->get('/', 'FactureController::index');
    $routes->get('creer', 'FactureController::creer');
    $routes->post('enregistrer', 'FactureController::enregistrer');
    $routes->get('(:num)', 'FactureController::afficher/$1');
    $routes->post('(:num)/statut', 'FactureController::changerStatut/$1');
    $routes->get('(:num)/supprimer', 'FactureController::supprimer/$1');
});
```

### Fichiers à créer

- **Controller**: `app/Controllers/FactureController.php`
  - Méthodes: `index()`, `creer()`, `enregistrer()`, `afficher()`, `changerStatut()`, `supprimer()`
  
- **Model**: `app/Models/VenteModel.php`
  - Table: `ventes`
  
- **Model**: `app/Models/VenteDetailModel.php`
  - Table: `vente_details`
  
- **Model**: `app/Models/ClientModel.php`
  - Table: `clients`
  
- **Views**: `app/Views/factures/index.php`, `creer.php`, `afficher.php`

### Étapes de développement

1. Créer les tables clients, ventes, vente_details
2. Créer les Models
3. Créer le Controller avec transaction pour vente + détails
4. Créer les Views
5. Tester: création facture → vente + détails → affichage

---

## 12. RH - Employés

### Tables utiles

```sql
CREATE TABLE employes (
    id               SERIAL PRIMARY KEY,
    matricule        VARCHAR(30) UNIQUE,
    nom              VARCHAR(100) NOT NULL,
    prenom           VARCHAR(100),
    telephone        VARCHAR(30),
    email            VARCHAR(150),
    adresse          TEXT,
    poste            VARCHAR(100),
    salaire_base     NUMERIC(12,2),
    date_embauche    DATE,
    date_fin_contrat DATE,
    statut           VARCHAR(30) DEFAULT 'ACTIF',
    photo_profil     VARCHAR(255)
);

CREATE TABLE paiements_salaires (
    id            SERIAL PRIMARY KEY,
    employe_id    INTEGER REFERENCES employes(id),
    mois          INTEGER NOT NULL,
    annee         INTEGER NOT NULL,
    salaire_base  NUMERIC(12,2),
    primes        NUMERIC(12,2) DEFAULT 0,
    deductions    NUMERIC(12,2) DEFAULT 0,
    montant_paye  NUMERIC(12,2) NOT NULL,
    date_paiement TIMESTAMP DEFAULT NOW(),
    commentaire   TEXT
);
```

### Routes.php

```php
$routes->get('employes', 'EmployeController::index', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->get('employes/create', 'EmployeController::create', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->post('employes/store', 'EmployeController::store', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->get('employes/show/(:num)', 'EmployeController::show/$1', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->get('employes/edit/(:num)', 'EmployeController::edit/$1', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->post('employes/update/(:num)', 'EmployeController::update/$1', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->get('employes/delete/(:num)', 'EmployeController::fire/$1', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->post('employes/upload-photo/(:num)', 'EmployeController::uploadPhoto/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/EmployeController.php`
  - Méthodes: `index()`, `create()`, `store()`, `show()`, `edit()`, `update()`, `fire()`, `uploadPhoto()`
  
- **Model**: `app/Models/EmployeModel.php`
  - Table: `employes`
  
- **Model**: `app/Models/PaiementSalaireModel.php`
  - Table: `paiements_salaires`
  
- **Views**: `app/Views/employes/index.php`, `create.php`, `edit.php`, `show.php`

### Étapes de développement

1. Créer les tables employes et paiements_salaires
2. Créer les Models
3. Créer le Controller avec CRUD + upload photo
4. Créer les Views
5. Tester: CRUD employés + enregistrement salaires

---

## 13. Contrats & Entreprises

### Tables utiles

```sql
CREATE TABLE entreprise (
    id        SERIAL PRIMARY KEY,
    nom       VARCHAR(200) NOT NULL,
    telephone VARCHAR(50),
    email     VARCHAR(150)
);

CREATE TABLE statut (
    id  SERIAL PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE contrats (
    id              SERIAL PRIMARY KEY,
    sujet           VARCHAR(200),
    entreprise_id   INTEGER REFERENCES entreprise(id),
    statut_id       INTEGER REFERENCES statut(id),
    description     TEXT,
    date_signature  DATE,
    date_expiration DATE,
    date_creation   TIMESTAMP DEFAULT NOW()
);
```

### Routes.php

```php
$routes->get('/entreprise', 'EntrepriseController::index');
$routes->get('/entreprise/ajout', 'EntrepriseController::ajout');
$routes->post('/entreprise/save', 'EntrepriseController::save');
$routes->get('/entreprise/modifier/(:num)', 'EntrepriseController::modifier/$1');
$routes->post('/entreprise/update/(:num)', 'EntrepriseController::update/$1');
$routes->post('/entreprise/supprimer/(:num)', 'EntrepriseController::supprimer/$1');

$routes->get('/contrat', 'ContratController::index');
$routes->get('/contrat/ajout', 'ContratController::ajout');
$routes->post('/contrat/save', 'ContratController::save');
$routes->get('/contrat/detail/(:num)', 'ContratController::detail/$1');
$routes->get('/contrat/pdf/(:num)', 'ContratController::pdf/$1');
$routes->get('/contrat/modifier/(:num)', 'ContratController::modifier/$1');
$routes->post('/contrat/update/(:num)', 'ContratController::update/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/EntrepriseController.php`
  - Méthodes: `index()`, `ajout()`, `save()`, `modifier()`, `update()`, `supprimer()`
  
- **Controller**: `app/Controllers/ContratController.php`
  - Méthodes: `index()`, `ajout()`, `save()`, `detail()`, `pdf()`, `modifier()`, `update()`
  
- **Model**: `app/Models/EntrepriseModel.php`
  - Table: `entreprise`
  
- **Model**: `app/Models/ContratModel.php`
  - Table: `contrats`
  
- **Model**: `app/Models/StatutModel.php`
  - Table: `statut`
  
- **Views**: `app/Views/entreprise/index.php`, `ajout.php`, `app/Views/contrats/index.php`, `ajout.php`, `detail.php`

### Étapes de développement

1. Créer les tables entreprise, statut, contrats
2. Créer les Models
3. Créer les Controllers EntrepriseController et ContratController
4. Créer les Views
5. Implémenter export PDF pour contrats
6. Tester: CRUD entreprises + CRUD contrats + PDF

---

## 14. Livreurs & Livraisons

### Tables utiles

```sql
CREATE TABLE livreurs (
    id         SERIAL PRIMARY KEY,
    nom        VARCHAR(150),
    telephone  VARCHAR(50),
    email      VARCHAR(150),
    actif      BOOLEAN DEFAULT TRUE
);

CREATE TABLE livraisons (
    id              SERIAL PRIMARY KEY,
    sortie_id       INTEGER REFERENCES sorties(id),
    livreur_id      INTEGER REFERENCES livreurs(id),
    date_livraison  TIMESTAMP,
    statut          VARCHAR(50) DEFAULT 'EN_ATTENTE',
    commentaire     TEXT
);
```

### Routes.php

```php
$routes->get('livraisons', 'LivraisonController::index', ['filter' => 'role:LIVREUR,ADMIN,RESPONSABLE']);
$routes->get('livraisons/create', 'LivraisonController::create', ['filter' => 'role:LIVREUR,ADMIN,RESPONSABLE']);
$routes->post('livraisons/store', 'LivraisonController::store', ['filter' => 'role:LIVREUR,ADMIN,RESPONSABLE']);
$routes->get('livraisons/status/(:num)/(:any)', 'LivraisonController::updateStatut/$1/$2');
$routes->get('livreurs', 'LivreurController::index');
$routes->post('livreurs/store', 'LivreurController::store');
$routes->get('livreurs/edit/(:num)', 'LivreurController::edit/$1');
$routes->post('livreurs/update/(:num)', 'LivreurController::update/$1');
```

### Fichiers à créer

- **Controller**: `app/Controllers/LivraisonController.php`
  - Méthodes: `index()`, `create()`, `store()`, `updateStatut()`
  
- **Controller**: `app/Controllers/LivreurController.php`
  - Méthodes: `index()`, `store()`, `edit()`, `update()`
  
- **Model**: `app/Models/LivraisonModel.php`
  - Table: `livraisons`
  
- **Model**: `app/Models/LivreurModel.php`
  - Table: `livreurs`
  
- **Views**: `app/Views/livraisons/index.php`, `create.php`, `app/Views/livreurs/index.php`, `edit.php`

### Étapes de développement

1. Créer les tables livreurs et livraisons
2. Créer les Models
3. Créer les Controllers
4. Créer les Views
5. Tester: CRUD livreurs + création livraisons + mise à jour statut

---

## 15. Planning (Calendrier)

### Tables utiles

```sql
CREATE TABLE planning (
    id             SERIAL PRIMARY KEY,
    employe_id     INTEGER REFERENCES employes(id),
    date_debut     TIMESTAMP,
    date_fin       TIMESTAMP,
    type_evenement VARCHAR(50),
    description    TEXT
);
```

### Routes.php

```php
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
```

### Fichiers à créer

- **Controller**: `app/Controllers/PlanningController.php`
  - Méthodes: `index()`, `events()`, `liste()`, `ajouter()`, `save()`, `details()`, `modifier()`, `update()`, `delete()`
  
- **Model**: `app/Models/PlanningModel.php`
  - Table: `planning`
  
- **Views**: `app/Views/planning/index.php`, `calendrier.php`, `ajouter.php`, `modifier.php`, `details.php`

### Étapes de développement

1. Créer la table planning
2. Créer le Model PlanningModel
3. Créer le Controller avec API JSON pour FullCalendar
4. Créer les Views avec calendrier interactif
5. Tester: affichage calendrier + CRUD événements

---

## 16. Statistiques

### Tables utiles

```sql
-- Réutilise: entrees_matiere_premiere, sorties, finances, supermarches, fournisseurs
```

### Routes.php

```php
$routes->get('statistiques', 'Statistiques::index');
$routes->get('statistiques/vente', 'StatistiquesVente::index');
$routes->group('statistiques', function($routes) {
    $routes->get('encaissements', 'StatistiquesVente::encaissements');
    $routes->get('decaissements', 'StatistiquesVente::decaissements');
    $routes->get('api/graphique', 'StatistiquesVente::apiGraphique');
});
```

### Fichiers à créer

- **Controller**: `app/Controllers/Statistiques.php`
  - Méthodes: `index()`
  
- **Controller**: `app/Controllers/StatistiquesVente.php`
  - Méthodes: `index()`, `encaissements()`, `decaissements()`, `apiGraphique()`
  
- **Model**: `app/Models/StatsVenteModel.php`
  
- **Views**: `app/Views/statistiques/index.php`, `tableau_de_bord.php`, `statistiques.php`

### Étapes de développement

1. Créer le Model StatsVenteModel avec méthodes d'agrégation
2. Créer les Controllers
3. Créer les Views avec graphiques
4. Implémenter API JSON pour graphiques
5. Tester: affichage statistiques + graphiques dynamiques

---

## 17. Notifications

### Tables utiles

```sql
CREATE TABLE notifications (
    id             SERIAL PRIMARY KEY,
    utilisateur_id INTEGER REFERENCES utilisateurs(id),
    titre          VARCHAR(200),
    message        TEXT,
    lu             BOOLEAN DEFAULT FALSE,
    date_creation  TIMESTAMP DEFAULT NOW()
);
```

### Routes.php

```php
$routes->get('notification/liste', 'Notification::liste');
$routes->get('notification/count', 'Notification::count');
$routes->post('notification/lire/(:num)', 'Notification::lire/$1');
$routes->post('notification/create', 'Notification::create');
```

### Fichiers à créer

- **Controller**: `app/Controllers/Notification.php`
  - Méthodes: `liste()`, `count()`, `lire()`, `create()`
  
- **Model**: `app/Models/NotificationModel.php`
  - Table: `notifications`
  
- **View**: `app/Views/notification/liste.php`

### Étapes de développement

1. Créer la table notifications
2. Créer le Model NotificationModel
3. Créer le Controller avec API AJAX
4. Créer la View
5. Intégrer dans le header (badge + liste)
6. Tester: création notifications → affichage badge → marquer lu

---

## 18. Profil Utilisateur

### Tables utiles

```sql
-- Réutilise: utilisateurs (avec photo_profil)
```

### Routes.php

```php
$routes->get('profil', 'ProfilController::index');
$routes->post('profil/update', 'ProfilController::update');
```

### Fichiers à créer

- **Controller**: `app/Controllers/ProfilController.php`
  - Méthodes: `index()`, `update()`
  
- **View**: `app/Views/profil/index.php`

### Étapes de développement

1. Créer le Controller ProfilController
2. Créer la View avec formulaire profil
3. Implémenter update() avec upload photo
4. Mettre à jour session après modification
5. Tester: modification profil + upload photo

---

## Ordre Recommandé de Développement

1. **Authentification** - Fondation de sécurité
2. **Dashboard** - Vue d'ensemble
3. **Fournisseurs** - Référentiel de base
4. **Stock MP** - Entrées et CUMP
5. **Transformations** - Production
6. **Supermarchés** - Référentiel clients
7. **Sorties** - Ventes
8. **Configuration** - Seuils d'alerte
9. **Valeur Stock** - Valorisation
10. **Finances** - Trésorerie
11. **Factures** - Documentation ventes
12. **RH** - Employés et salaires
13. **Contrats** - Partenariats
14. **Livreurs** - Logistique
15. **Planning** - Calendrier
16. **Statistiques** - Analyse
17. **Notifications** - Alertes temps réel
18. **Profil** - Gestion compte

---

## Configuration Globale

### Filtres de Sécurité

**Fichier**: `app/Config/Filters.php`

```php
public array $globals = [
    'before' => [
        'auth' => ['except' => ['/', 'login', 'logout']],
    ],
];
```

**Filtres à créer**:
- `AuthFilter.php` - Vérifie session `isLoggedIn`
- `RoleFilter.php` - Vérifie rôle utilisateur

### Base de Données

**Fichier**: `app/Config/Database.php`

Configurer la connexion PostgreSQL avec les paramètres du serveur.

---

## Checklist Finale

- [ ] Base PostgreSQL créée via `all_in_one.sql`
- [ ] Connexion BDD configurée
- [ ] Authentification fonctionnelle
- [ ] Filtres de sécurité actifs
- [ ] Dashboard opérationnel
- [ ] Référentiels (fournisseurs, supermarchés, types bocaux)
- [ ] Flux complet: Stock MP → Transformation → Stock PF → Sortie
- [ ] CUMP recalculé automatiquement
- [ ] Seuils d'alerte configurables
- [ ] Finances avec rapports
- [ ] Factures et livraisons
- [ ] RH complète
- [ ] Contrats avec export PDF
- [ ] Statistiques et exports
- [ ] Notifications temps réel
- [ ] Profil utilisateur
