# Guide — Module de Gestion de Stock (Projet Miel Arovia)

> Ce guide explique, étape par étape, comment construire un module de gestion de stock avec **CodeIgniter4** et **PostgreSQL**, en partant de zéro. Il a été rédigé pour que tu puisses le reprendre toi-même et l'expliquer à ton groupe.

---

## 1. Le principe métier (à comprendre avant de coder)

Le problème de départ : on achète du miel **brut** chez des apiculteurs (en litres), mais on vend du miel **en bocal** (10cl, 25cl, 50cl). Ce ne sont pas le même produit, donc on ne peut pas les gérer dans un seul stock.

**La solution : deux stocks séparés, reliés par une transformation.**

```
Fournisseurs → Entrée matière première (litres) → [STOCK MATIÈRE PREMIÈRE]
                                                            │
                                                      Transformation
                                                     (mise en bocal)
                                                            │
                                                            ▼
                                                  [STOCK PRODUIT FINI]
                                                  (bocaux 10cl/25cl/50cl)
                                                            │
                                                         Sortie
                                                      (vente/distribution)
```

- **Le stock matière première** : combien de litres de miel brut on a en réserve, et leur valeur.
- **Le stock produit fini** : combien de bocaux de chaque type sont prêts à être vendus.
- **La transformation** : l'opération qui retire des litres du premier stock et ajoute des bocaux au deuxième, **en même temps, dans une seule transaction**.

Ce principe (matière première → transformation → produit fini) s'applique à n'importe quel projet de fabrication/transformation, pas seulement le miel.

---

## 2. Le CUMP (Coût Unitaire Moyen Pondéré)

Comme on achète du miel à plusieurs fournisseurs à des prix différents, on a besoin d'**un seul prix moyen** pour valoriser le stock. C'est le rôle du CUMP.

**Formule :**
```
CUMP = (valeur du stock existant + valeur de la nouvelle entrée) / (quantité existante + quantité entrante)
```

**Règle d'or à retenir** : le CUMP **change uniquement à l'entrée** (nouvel achat). Une sortie ou une transformation ne change jamais le CUMP — elle utilise le CUMP déjà calculé, et réduit la quantité/valeur proportionnellement.

### Exemple chiffré

| Mouvement | Quantité (L) | Prix unitaire | CUMP après |
|---|---|---|---|
| Entrée fournisseur A | 50 L | 4 000 Ar/L | 4 000 Ar/L |
| Entrée fournisseur B | 30 L | 5 000 Ar/L | (200000+150000)/80 = **4 375 Ar/L** |
| Sortie (transformation) | 20 L | — | toujours **4 375 Ar/L** |
| Entrée fournisseur C | 10 L | 4 800 Ar/L | nouveau calcul... |

---

## 3. Stack technique utilisée

- **Backend** : PHP avec le framework **CodeIgniter4** (architecture MVC)
- **Base de données** : **PostgreSQL**
- **Frontend** (pour les graphiques) : **Chart.js** via CDN
- **Outil d'administration de la base** : **pgAdmin 4**

---

## 4. Mise en place de PostgreSQL

### 4.1 Créer la base de données

Dans pgAdmin :
1. Clic droit sur **Databases** → **Create** → **Database...**
2. Nom : `gestion_miel`

### 4.2 Créer les tables

Ouvrir le **Query Tool** sur la base, puis exécuter :

```sql
CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    contact VARCHAR(100),
    localisation VARCHAR(150)
);

CREATE TABLE entrees_matiere_premiere (
    id SERIAL PRIMARY KEY,
    fournisseur_id INTEGER REFERENCES fournisseurs(id),
    numero_lot VARCHAR(30) UNIQUE,
    date_entree TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres NUMERIC(10,2) NOT NULL,
    prix_unitaire NUMERIC(10,2) NOT NULL,
    valeur_totale NUMERIC(12,2) NOT NULL,
    cump_apres_entree NUMERIC(10,2) NOT NULL
);

CREATE TABLE stock_matiere_premiere (
    id SERIAL PRIMARY KEY,
    quantite_litres NUMERIC(10,2) NOT NULL DEFAULT 0,
    valeur_stock NUMERIC(12,2) NOT NULL DEFAULT 0,
    cump_actuel NUMERIC(10,2) NOT NULL DEFAULT 0,
    derniere_maj TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE transformations (
    id SERIAL PRIMARY KEY,
    date_transformation TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres_utilisee NUMERIC(10,2) NOT NULL,
    cump_applique NUMERIC(10,2) NOT NULL,
    valeur_sortie NUMERIC(12,2) NOT NULL
);

CREATE TABLE types_bocaux (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(20) NOT NULL,
    volume_litres NUMERIC(4,2) NOT NULL,
    cible VARCHAR(20) NOT NULL,
    prix_vente NUMERIC(10,2)
);

CREATE TABLE transformations_detail (
    id SERIAL PRIMARY KEY,
    transformation_id INTEGER REFERENCES transformations(id),
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite_produite INTEGER NOT NULL
);

CREATE TABLE stock_produit_fini (
    type_bocal_id INTEGER PRIMARY KEY REFERENCES types_bocaux(id),
    quantite_disponible INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE sorties (
    id SERIAL PRIMARY KEY,
    date_sortie TIMESTAMP NOT NULL DEFAULT NOW(),
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite INTEGER NOT NULL,
    destinataire_type VARCHAR(20) NOT NULL,
    destinataire_nom VARCHAR(150),
    prix_vente_unitaire NUMERIC(10,2) NOT NULL,
    valeur_totale NUMERIC(12,2) NOT NULL
);
```

### 4.3 Insérer les données de départ obligatoires

```sql
INSERT INTO stock_matiere_premiere (quantite_litres, valeur_stock, cump_actuel)
VALUES (0, 0, 0);

INSERT INTO types_bocaux (nom, volume_litres, cible, prix_vente) VALUES
('10cl', 0.10, 'hotel', 15000),
('25cl', 0.25, 'particulier', 25000),
('50cl', 0.50, 'touriste', 40000);

INSERT INTO stock_produit_fini (type_bocal_id, quantite_disponible)
SELECT id, 0 FROM types_bocaux;
```

---

## 5. Connecter CodeIgniter4 à PostgreSQL

### 5.1 Le fichier `.env`

À la racine du projet, copier `env` en `.env`, puis configurer :

```
database.default.hostname = localhost
database.default.database = gestion_miel
database.default.username = postgres
database.default.password = ton_mot_de_passe
database.default.DBDriver = Postgre
database.default.port = 5432
database.default.charset = utf8
```

**Pièges fréquents rencontrés :**
- `DBDriver` doit être écrit `Postgre` (sans "SQL")
- Le `charset` par défaut de CI4 est `utf8mb4` (spécifique à MySQL) — il faut le forcer à `utf8` pour PostgreSQL, sinon erreur `valeur invalide pour le paramètre « client_encoding »`
- Il faut activer les extensions PHP `pgsql` et `pdo_pgsql` dans `php.ini` (enlever le `;` devant), et vérifier que les fichiers `.dll` correspondants existent dans le dossier `ext` de PHP

### 5.2 Tester la connexion

```
php spark db:table fournisseurs
```

Si ça affiche la structure de la table, la connexion fonctionne.

---

## 6. Le pattern MVC en CodeIgniter4

Chaque fonctionnalité suit toujours la même structure à 4 niveaux :

```
Model       → représente une table, contient la logique d'accès aux données
Controller  → reçoit les requêtes, appelle le Model, retourne une Vue
Routes      → associe une URL à une méthode du Controller
Vue         → le HTML affiché à l'utilisateur
```

### Exemple complet avec `fournisseurs`

**Générer les fichiers :**
```
php spark make:model FournisseurModel
php spark make:controller Fournisseurs
```

**Model** (`app/Models/FournisseurModel.php`) :
```php
<?php

namespace App\Models;

use CodeIgniter\Model;

class FournisseurModel extends Model
{
    protected $table            = 'fournisseurs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'contact', 'localisation'];
    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[150]',
    ];
}
```

> `$allowedFields` est une protection importante : seuls ces champs peuvent être insérés/modifiés via le Model, ça empêche l'injection de champs non prévus.

**Controller** (`app/Controllers/Fournisseurs.php`) — méthodes `index()`, `new()`, `create()`, `edit()`, `update()`, `delete()` : chaque méthode appelle le Model et retourne une vue ou une redirection.

**Routes** (`app/Config/Routes.php`) :
```php
$routes->get('fournisseurs', 'Fournisseurs::index');
$routes->get('fournisseurs/new', 'Fournisseurs::new');
$routes->post('fournisseurs', 'Fournisseurs::create');
$routes->get('fournisseurs/(:num)/edit', 'Fournisseurs::edit/$1');
$routes->post('fournisseurs/(:num)', 'Fournisseurs::update/$1');
$routes->get('fournisseurs/(:num)/delete', 'Fournisseurs::delete/$1');
```

**Vue** (`app/Views/fournisseurs/index.php`) — affiche la liste avec une boucle `foreach`, en utilisant toujours `esc()` autour des données affichées (protection contre l'injection HTML/JS).

> Ce pattern (Model + Controller + Routes + 1 ou 2 vues) se répète **identiquement** pour chaque entité simple de l'application (fournisseurs, types de bocaux, etc.).

---

## 7. La logique CUMP avec transaction sécurisée

C'est la partie la plus technique du projet. Le code vit dans `StockMatierePremiereModel::enregistrerEntree()`.

**Principe clé : `FOR UPDATE` + transaction.**

```php
public function enregistrerEntree(int $fournisseurId, float $quantite, float $prixUnitaire): bool
{
    $db = \Config\Database::connect();
    $db->transStart();

    // Verrouille la ligne de stock pendant le calcul
    $stock = $db->query('SELECT * FROM stock_matiere_premiere LIMIT 1 FOR UPDATE')->getRowArray();

    $valeurEntree     = $quantite * $prixUnitaire;
    $nouvelleQuantite = $stock['quantite_litres'] + $quantite;
    $nouvelleValeur   = $stock['valeur_stock'] + $valeurEntree;
    $nouveauCump      = $nouvelleValeur / $nouvelleQuantite;

    // ... insertion historique + mise à jour du stock ...

    $db->transComplete();
    return $db->transStatus();
}
```

**Pourquoi c'est important :**
- `FOR UPDATE` verrouille la ligne pendant l'opération : si deux utilisateurs enregistrent une entrée en même temps, le deuxième doit attendre que le premier termine. Sans ça, le CUMP calculé pourrait être faux.
- `transStart()` / `transComplete()` garantissent que **toutes les écritures réussissent ensemble, ou aucune** — si une étape échoue, tout est annulé, pour éviter d'avoir un historique incohérent avec l'état du stock.

Le même principe (transaction + `FOR UPDATE`) est réutilisé pour :
- `TransformationModel::enregistrerTransformation()` (mise en bocal)
- `SortieModel::enregistrerSortie()` (vente)

---

## 8. La transformation (mise en bocal)

**Le piège à éviter** : ne jamais faire confiance au calcul fait côté navigateur (JavaScript). Même si un calculateur JS affiche en temps réel le volume nécessaire pour le confort de l'utilisateur, **le serveur revérifie toujours tout** avant d'enregistrer quoi que ce soit :

```php
$volumeTotalNecessaire = 0;
foreach ($typesBocaux as $type) {
    $quantiteDemandee       = $repartition[$type['id']] ?? 0;
    $volumeTotalNecessaire += $quantiteDemandee * $type['volume_litres'];
}

if ($volumeTotalNecessaire > $stockMP['quantite_litres']) {
    // refuser
}
```

Cette transformation fait, dans une seule transaction :
1. Décrémente le stock matière première (au CUMP actuel — qui ne change pas)
2. Enregistre la transformation + son détail par type de bocal
3. Incrémente le stock produit fini pour chaque type concerné

---

## 9. Les filtres

Tous les filtres suivent le même principe : un formulaire en `method="get"`, et le Model qui construit sa requête conditionnellement.

```php
public function getEntreesAvecFournisseur(array $filtres = [])
{
    $builder = $this->select('...')->join('...');

    if (! empty($filtres['fournisseur_id'])) {
        $builder->where('fournisseur_id', $filtres['fournisseur_id']);
    }
    if (! empty($filtres['date_debut'])) {
        $builder->where('date_entree >=', $filtres['date_debut'] . ' 00:00:00');
    }
    // ...

    return $builder->findAll();
}
```

Comme le formulaire est en `GET`, les filtres apparaissent dans l'URL (`?fournisseur_id=2&date_debut=2026-06-01`), ce qui permet de recharger ou partager une vue filtrée.

---

## 10. Les statistiques (Chart.js)

Le Model agrège les données avec `GROUP BY` :

```php
public function getStatistiquesParDate(): array
{
    return $this->select('DATE(date_entree) as jour, SUM(quantite_litres) as total_litres')
                ->groupBy('DATE(date_entree)')
                ->orderBy('jour', 'ASC')
                ->findAll();
}
```

Le Controller transmet ces données à la vue, qui les convertit en JSON pour Chart.js :

```php
const entreesParDate = <?= json_encode($entreesParDate) ?>;
```

Chart.js (chargé via CDN, pas d'installation nécessaire) dessine ensuite la courbe ou le camembert à partir de ce JSON.

---

## 11. La valeur de stock consolidée

Deux façons de valoriser le stock produit fini, à bien distinguer :

- **Valeur comptable (au coût)** = quantité × (CUMP actuel × volume du bocal) → ce que ça t'a réellement coûté
- **Valeur de vente potentielle** = quantité × prix de vente catalogue → ton chiffre d'affaires potentiel si tout est vendu

La page combine le stock matière première + le stock produit fini valorisé au coût pour donner une **valeur comptable totale** du stock.

---

## 12. Bonnes pratiques générales appliquées partout

- **`esc()`** autour de toute donnée affichée venant de la base (protection XSS)
- **`csrf_field()`** dans chaque formulaire (protection CSRF, obligatoire par défaut dans CI4)
- **`$allowedFields`** dans chaque Model (protection contre l'injection de champs)
- **Validation** (`$this->validate()` ou `$validationRules` du Model) avant toute insertion
- **Transactions + `FOR UPDATE`** dès qu'une opération touche plusieurs tables ou doit rester cohérente en cas d'accès concurrent
- **Recalcul côté serveur** de toute valeur critique (jamais confiance aveugle au JavaScript envoyé par le formulaire)

---

## 13. Checklist avant la soutenance

- [ ] Remettre `CI_ENVIRONMENT = production` dans `.env` (ne pas exposer les erreurs détaillées)
- [ ] Transformer les routes de suppression en `POST` plutôt qu'en simple lien `GET`
- [ ] Vérifier qu'aucun mot de passe ou donnée sensible n'apparaît à l'écran pendant la démo
- [ ] Tester le scénario complet une dernière fois : fournisseur → entrée → transformation → vente → vérifier stats et valeur de stock
- [ ] Préparer quelques données de démonstration réalistes (plusieurs fournisseurs, plusieurs dates, plusieurs types de bocaux vendus) pour que les graphiques et filtres soient parlants à l'oral

---

## 14. Pour aller plus loin avec ton groupe

Si d'autres membres veulent ajouter des modules sur le même modèle (par exemple un module clients, ou un module de gestion des hôtels partenaires), le même pattern s'applique à chaque fois :

1. Créer la/les table(s) en SQL
2. `php spark make:model` + `php spark make:controller`
3. Définir les routes
4. Créer les vues
5. Si l'opération touche plusieurs tables en même temps → ajouter une transaction avec `FOR UPDATE`
