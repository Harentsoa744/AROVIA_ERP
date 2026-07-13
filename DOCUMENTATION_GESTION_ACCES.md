# Documentation - Gestion des Accès et Rôles des Utilisateurs

## Table des matières
1. [Vue d'ensemble du système](#vue-densemble)
2. [Authentification des utilisateurs](#authentification)
3. [Gestion des rôles](#gestion-des-rôles)
4. [Filtres de protection](#filtres-de-protection)
5. [Protection des routes](#protection-des-routes)
6. [Sidebar dynamique par rôle](#sidebar-dynamique)
7. [Modifier les accès](#modifier-les-accès)

---

## Vue d'ensemble

Le système de gestion des accès de l'application AROVIA est basé sur :
- **Authentification** : Vérification de l'identité de l'utilisateur
- **Autorisation** : Vérification des permissions basées sur le rôle
- **Filtres CodeIgniter 4** : Interception des requêtes pour valider les accès
- **Session PHP** : Stockage des informations utilisateur connecté

**Fichiers clés :**
- `app/Controllers/AuthController.php` - Gestion de la connexion/déconnexion
- `app/Filters/AuthFilter.php` - Filtre d'authentification global
- `app/Filters/RoleFilter.php` - Filtre de contrôle d'accès par rôle
- `app/Config/Filters.php` - Configuration des filtres
- `app/Config/Routes.php` - Définition des routes et leur protection
- `public/utils/side_bar.php` - Menu sidebar basé sur les rôles

---

## Authentification

### Fichier : `app/Controllers/AuthController.php`

Le contrôleur `AuthController` gère le processus de connexion et de déconnexion.

#### Méthode `login()`

```php
public function login()
{
    // 1. Vérifie si déjà connecté
    if (session()->get('user_id')) {
        return redirect()->to(base_url('home'));
    }

    // 2. Récupère les identifiants
    $email    = trim((string) $this->request->getPost('email'));
    $password = trim((string) $this->request->getPost('password'));

    // 3. Vérifie via le modèle UtilisateurModel
    $user = $this->utilisateurModel->login($email, $password);

    if ($user) {
        // 4. Stocke les infos en session
        session()->set([
            'user_id'     => $user['id'],
            'user_nom'    => $user['nom'],
            'user_prenom' => $user['prenom'],
            'user_email'  => $user['email'],
            'user_role'   => $user['role_nom'] ?? 'Utilisateur',
            'isLoggedIn'  => true
        ]);

        return redirect()->to(base_url('home'));
    }

    return view('auth/login', ['error' => 'Identifiants invalides...']);
}
```

**Variables de session créées :**
- `user_id` : ID de l'utilisateur
- `user_nom` : Nom de l'utilisateur
- `user_prenom` : Prénom de l'utilisateur
- `user_email` : Email de l'utilisateur
- `user_role` : **Rôle de l'utilisateur** (ex: ADMIN, LIVREUR, MAGASINIER)
- `isLoggedIn` : Booléen indiquant si connecté

### Fichier : `app/Models/UtilisateurModel.php`

La méthode `login()` vérifie les identifiants et retourne les données utilisateur avec le rôle.

```php
public function login(string $email, string $password): ?array
{
    $user = $this->select('utilisateurs.*, roles.nom as role_nom')
        ->join('roles', 'roles.id = utilisateurs.role_id', 'left')
        ->where('utilisateurs.email', $email)
        ->where('utilisateurs.actif', true)
        ->first();

    // Vérification du mot de passe (hash, legacy, compatibilité)
    if (password_verify($password, $storedPassword)) {
        return $user;
    }
    // ...
}
```

---

## Gestion des rôles

### Rôles dans la base de données

Les rôles sont stockés dans la table `roles` :

```sql
INSERT INTO roles (nom) VALUES
('ADMIN'), 
('COMPTABLE'), 
('MAGASINIER'), 
('LIVREUR'), 
('RESPONSABLE');
```

**Rôles actuels :**
- `ADMIN` : Accès complet à toutes les fonctionnalités
- `RESPONSABLE` : Accès complet (similaire à ADMIN)
- `LIVREUR` : Accès aux livraisons et planning
- `MAGASINIER` : Accès au stock et ventes
- `COMPTABLE` : Accès au stock et ventes (similaire à MAGASINIER)

### Association utilisateur-rôle

Chaque utilisateur dans la table `utilisateurs` a un `role_id` qui fait référence à la table `roles`.

---

## Filtres de protection

### Fichier : `app/Filters/AuthFilter.php`

Filtre global qui vérifie si l'utilisateur est connecté.

```php
public function before(RequestInterface $request, $arguments = null)
{
    if (! session()->get('isLoggedIn')) {
        return redirect()->to(base_url('/'));
    }
}
```

**Activation :** Configuré dans `app/Config/Filters.php` pour s'appliquer à toutes les routes sauf `/`, `login`, `logout`.

### Fichier : `app/Filters/RoleFilter.php`

Filtre qui vérifie si l'utilisateur a le rôle requis pour accéder à une page.

```php
public function before(RequestInterface $request, $arguments = null)
{
    // 1. Vérifie si connecté
    if (! session()->get('isLoggedIn')) {
        return redirect()->to(base_url('/'));
    }

    $userRole = session()->get('user_role');
    
    // 2. Si aucun argument, autorise tous les rôles connectés
    if (empty($arguments)) {
        return;
    }

    // 3. Comparaison insensible à la casse
    $userRoleLower = strtolower($userRole ?? '');
    $allowedRoles = array_map('strtolower', $arguments);
    
    // 4. Vérifie si le rôle est autorisé
    if (! in_array($userRoleLower, $allowedRoles, true)) {
        return redirect()->to(base_url('home'))->with('error', 'Accès non autorisé.');
    }
}
```

**Utilisation :** Le filtre reçoit des arguments (rôles autorisés) depuis les routes.

---

## Protection des routes

### Fichier : `app/Config/Routes.php`

Les routes sont protégées en ajoutant l'option `filter` avec le filtre `role` et les rôles autorisés.

#### Exemples de routes protégées

```php
// Routes employés - Admin uniquement
$routes->get('employes', 'EmployeController::index', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->get('employes/create', 'EmployeController::create', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->post('employes/store', 'EmployeController::store', ['filter' => 'role:ADMIN,RESPONSABLE']);

// Routes finances - Admin uniquement
$routes->get('finances', 'Finances::index', ['filter' => 'role:ADMIN,RESPONSABLE']);
$routes->post('finances/store', 'Finances::store', ['filter' => 'role:ADMIN,RESPONSABLE']);

// Routes livraisons - Livreur et Admin
$routes->get('livraisons', 'LivraisonController::index', ['filter' => 'role:LIVREUR,ADMIN,RESPONSABLE']);
$routes->get('livraisons/create', 'LivraisonController::create', ['filter' => 'role:LIVREUR,ADMIN,RESPONSABLE']);

// Routes sorties (ventes) - Magasinier/Comptable et Admin
$routes->get('sorties', 'Sorties::index', ['filter' => 'role:MAGASINIER,COMPTABLE,ADMIN,RESPONSABLE']);
$routes->post('sorties', 'Sorties::create', ['filter' => 'role:MAGASINIER,COMPTABLE,ADMIN,RESPONSABLE']);
```

**Syntaxe :**
```php
$routes->get('url', 'Controller::method', ['filter' => 'role:ROLE1,ROLE2,ROLE3']);
```

**Rôles séparés par des virgules** = OU logique (au moins un rôle requis).

---

## Sidebar dynamique

### Fichier : `public/utils/side_bar.php`

Le sidebar affiche les liens de navigation selon le rôle de l'utilisateur connecté.

#### Structure du code

```php
<?php 
$userRole = session()->get('user_role');
?>

<?php if (strtolower($userRole ?? '') === 'admin' || strtolower($userRole ?? '') === 'responsable'): ?>
    <!-- Menu complet pour Admin/Responsable -->
    <div class="nav-group">
        <div class="nav-group-header">Gestion de stock</div>
        <div class="nav-submenu">
            <a href="/fournisseurs">Fournisseurs</a>
            <a href="/supermarches">Supermarchés</a>
            <a href="/entrees-matiere-premiere">Entrées matière première</a>
            <a href="/transformations">Transformations</a>
            <a href="/sorties">Sorties (ventes)</a>
            <a href="/valeur-stock">Valeur du stock</a>
        </div>
    </div>
    <a href="/contrat">Gestion de contrat</a>
    <a href="/employes">Gestion Employés</a>
    <a href="/finances">Finance</a>
    <a href="/statistiques">Statistiques vente</a>
    <a href="/livraisons">Distribution</a>
    <a href="/emploi-temps">Emploi du temps</a>

<?php elseif (strtolower($userRole ?? '') === 'livreur'): ?>
    <!-- Menu pour Livreur -->
    <a href="/livraisons">Distribution</a>
    <a href="/emploi-temps">Emploi du temps</a>

<?php elseif (strtolower($userRole ?? '') === 'magasinier' || strtolower($userRole ?? '') === 'comptable'): ?>
    <!-- Menu pour Magasinier/Comptable -->
    <div class="nav-group">
        <div class="nav-group-header">Gestion de stock</div>
        <div class="nav-submenu">
            <a href="/sorties">Sorties (ventes)</a>
            <a href="/valeur-stock">Valeur du stock</a>
        </div>
    </div>
    <a href="/statistiques">Statistiques vente</a>

<?php else: ?>
    <!-- Menu par défaut -->
    <div class="nav-group">
        <div class="nav-group-header">Gestion de stock</div>
        <div class="nav-submenu">
            <a href="/sorties">Sorties (ventes)</a>
        </div>
    </div>
<?php endif; ?>
```

**Points importants :**
- Comparaison insensible à la casse (`strtolower()`)
- Plusieurs rôles peuvent partager le même menu (ex: Admin et Responsable)
- Menu par défaut pour les rôles non définis

---

## Modifier les accès

### 1. Ajouter un nouveau rôle

#### Étape 1 : Créer le rôle dans la base de données

```sql
INSERT INTO roles (nom) VALUES ('NOUVEAU_ROLE');
```

#### Étape 2 : Mettre à jour le sidebar (`public/utils/side_bar.php`)

Ajouter une nouvelle condition pour le nouveau rôle :

```php
<?php elseif (strtolower($userRole ?? '') === 'nouveau_role'): ?>
    <!-- Menu pour NOUVEAU_ROLE -->
    <a href="/page1">Page 1</a>
    <a href="/page2">Page 2</a>
```

#### Étape 3 : Protéger les routes (`app/Config/Routes.php`)

Ajouter le nouveau rôle aux filtres des routes :

```php
// Avant
$routes->get('page1', 'PageController::index', ['filter' => 'role:ADMIN']);

// Après
$routes->get('page1', 'PageController::index', ['filter' => 'role:ADMIN,NOUVEAU_ROLE']);
```

---

### 2. Modifier les accès d'un rôle existant

#### Modifier le menu du sidebar

Dans `public/utils/side_bar.php`, modifiez la section correspondante au rôle :

```php
<?php elseif (strtolower($userRole ?? '') === 'livreur'): ?>
    <!-- Ajouter ou supprimer des liens ici -->
    <a href="/livraisons">Distribution</a>
    <a href="/nouvelle-page">Nouvelle page</a>  <!-- Nouveau lien -->
    <!-- <a href="/ancienne-page">Ancienne page</a> -->  <!-- Lien supprimé -->
```

#### Modifier la protection des routes

Dans `app/Config/Routes.php`, ajoutez ou retirez le rôle des filtres :

```php
// Ajouter le rôle LIVREUR à une route
$routes->get('nouvelle-page', 'PageController::index', ['filter' => 'role:ADMIN,LIVREUR']);

// Retirer le rôle LIVREUR d'une route
$routes->get('ancienne-page', 'PageController::index', ['filter' => 'role:ADMIN']);
```

---

### 3. Créer une nouvelle page avec protection

#### Étape 1 : Créer le contrôleur

```php
// app/Controllers/NouvellePageController.php
namespace App\Controllers;

class NouvellePageController extends BaseController
{
    public function index()
    {
        return view('nouvelle_page/index');
    }
}
```

#### Étape 2 : Créer la vue

```php
// app/Views/nouvelle_page/index.php
<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Page</title>
</head>
<body>
    <?php include 'utils/header.php'; ?>
    <?php include 'utils/side_bar.php'; ?>
    <main>
        <h1>Nouvelle Page</h1>
    </main>
</body>
</html>
```

#### Étape 3 : Ajouter la route avec protection

Dans `app/Config/Routes.php` :

```php
$routes->get('nouvelle-page', 'NouvellePageController::index', ['filter' => 'role:ADMIN']);
```

#### Étape 4 : Ajouter au sidebar (optionnel)

Dans `public/utils/side_bar.php`, ajoutez le lien dans la section du rôle approprié :

```php
<?php if (strtolower($userRole ?? '') === 'admin'): ?>
    <!-- ... autres liens ... -->
    <a href="/nouvelle-page">Nouvelle Page</a>
```

---

### 4. Désactiver la protection d'une page

#### Option 1 : Retirer le filtre de la route

```php
// Avant (protégé)
$routes->get('page-publique', 'PageController::index', ['filter' => 'role:ADMIN']);

// Après (publique)
$routes->get('page-publique', 'PageController::index');
```

#### Option 2 : Exclure de l'authentification globale

Dans `app/Config/Filters.php`, ajoutez la route aux exceptions du filtre `auth` :

```php
public array $globals = [
    'before' => [
        'auth' => ['except' => ['/', 'login', 'logout', 'page-publique']],
    ],
];
```

---

### 5. Ajouter une page accessible à tous les rôles connectés

#### Sans filtre de rôle

```php
// Accessible à tout utilisateur connecté (pas besoin de filtre role)
$routes->get('page-commune', 'PageController::index', ['filter' => 'auth']);
```

#### Avec tous les rôles explicitement

```php
// Accessible à tous les rôles listés
$routes->get('page-commune', 'PageController::index', ['filter' => 'role:ADMIN,RESPONSABLE,LIVREUR,MAGASINIER,COMPTABLE']);
```

---

## Résumé rapide des fichiers à modifier

| Action | Fichier à modifier |
|--------|-------------------|
| Ajouter un nouveau rôle | Base de données + `side_bar.php` + `Routes.php` |
| Modifier le menu d'un rôle | `public/utils/side_bar.php` |
| Modifier l'accès à une page | `app/Config/Routes.php` |
| Créer une nouvelle page protégée | Contrôleur + Vue + `Routes.php` + `side_bar.php` |
| Désactiver la protection | `app/Config/Routes.php` ou `app/Config/Filters.php` |
| Modifier le filtre d'authentification | `app/Filters/AuthFilter.php` |
| Modifier le filtre de rôle | `app/Filters/RoleFilter.php` |

---

## Bonnes pratiques

1. **Toujours utiliser les noms de rôles en majuscules** dans la base de données
2. **Comparaison insensible à la casse** dans le code PHP (strtolower)
3. **Protéger les routes au niveau de la configuration** plutôt que dans les contrôleurs
4. **Garder le sidebar synchronisé** avec les protections de routes
5. **Tester les accès** après chaque modification
6. **Utiliser des noms de rôles explicites** et cohérents

---

## Débogage des problèmes d'accès

### Problème : Accès refusé malgré le bon rôle

**Vérifications :**
1. Le rôle dans la session correspond-il au rôle en base de données ?
2. Le nom du rôle est-il correct (majuscules/minuscules) ?
3. Le filtre est-il correctement configuré dans la route ?
4. Le filtre `role` est-il enregistré dans `Filters.php` ?

### Problème : Sidebar n'affiche pas les bons liens

**Vérifications :**
1. La session contient-elle `user_role` ?
2. La comparaison dans le sidebar est-elle insensible à la casse ?
3. Le rôle correspond-il à l'une des conditions du sidebar ?

### Problème : Page accessible sans connexion

**Vérifications :**
1. Le filtre `auth` est-il actif dans `Filters.php` ?
2. La route est-elle exclue des exceptions du filtre `auth` ?
3. La session est-elle bien détruite à la déconnexion ?

---

## Contact et support

Pour toute question sur la gestion des accès, consultez :
- La documentation CodeIgniter 4 sur les filtres : https://codeigniter.com/userguide4/incoming/filters.html
- La documentation CodeIgniter 4 sur les routes : https://codeigniter.com/userguide4/incoming/routing.html
