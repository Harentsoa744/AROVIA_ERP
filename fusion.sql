-- ============================================================================
-- FICHIER    : fusion.sql
-- PROJET     : ERP Miel Arovia
-- BASE       : gestion_miel (PostgreSQL 16)
-- USAGE      : psql -U postgres -d gestion_miel -h 127.0.0.1 -f fusion.sql
-- ============================================================================

-- ============================================================================
-- 1. NETTOYAGE — DROP dans l'ordre des dépendances
-- ============================================================================
DROP TABLE IF EXISTS disponibilites_livreurs  CASCADE;
DROP TABLE IF EXISTS livraisons               CASCADE;
DROP TABLE IF EXISTS livreurs                 CASCADE;
DROP TABLE IF EXISTS vente_details            CASCADE;
DROP TABLE IF EXISTS ventes                   CASCADE;
DROP TABLE IF EXISTS clients                  CASCADE;
DROP TABLE IF EXISTS sorties                  CASCADE;
DROP TABLE IF EXISTS supermarches             CASCADE;
DROP TABLE IF EXISTS stock_produit_fini       CASCADE;
DROP TABLE IF EXISTS transformations_detail   CASCADE;
DROP TABLE IF EXISTS transformations          CASCADE;
DROP TABLE IF EXISTS types_bocaux             CASCADE;
DROP TABLE IF EXISTS entrees_matiere_premiere CASCADE;
DROP TABLE IF EXISTS stock_matiere_premiere   CASCADE;
DROP TABLE IF EXISTS fournisseurs             CASCADE;
DROP TABLE IF EXISTS mouvements_financiers    CASCADE;
DROP TABLE IF EXISTS comptes_tresorerie       CASCADE;
DROP TABLE IF EXISTS paiements_salaires       CASCADE;
DROP TABLE IF EXISTS planning                 CASCADE;
DROP TABLE IF EXISTS employes                 CASCADE;
DROP TABLE IF EXISTS contrats                 CASCADE;
DROP TABLE IF EXISTS entreprise               CASCADE;
DROP TABLE IF EXISTS statut                   CASCADE;
DROP TABLE IF EXISTS utilisateurs             CASCADE;
DROP TABLE IF EXISTS roles                    CASCADE;
DROP VIEW  IF EXISTS transactions             CASCADE;

-- ============================================================================
-- 2. UTILISATEURS & RÔLES
-- ============================================================================
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

-- ============================================================================
-- 3. RH
-- ============================================================================
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
    statut           VARCHAR(30) DEFAULT 'ACTIF'
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

CREATE TABLE planning (
    id             SERIAL PRIMARY KEY,
    employe_id     INTEGER REFERENCES employes(id),
    date_debut     TIMESTAMP,
    date_fin       TIMESTAMP,
    type_evenement VARCHAR(50),
    description    TEXT
);

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

-- ============================================================================
-- 4. ACHATS & STOCK MATIÈRE PREMIÈRE
-- ============================================================================

-- Fournisseurs de miel brut
CREATE TABLE fournisseurs (
    id           SERIAL PRIMARY KEY,
    nom          VARCHAR(150) NOT NULL,
    contact      VARCHAR(150),
    telephone    VARCHAR(50),
    email        VARCHAR(150),
    localisation VARCHAR(150)
);

-- État courant du stock de miel brut (une seule ligne, mise à jour en continu)
CREATE TABLE stock_matiere_premiere (
    id              SERIAL PRIMARY KEY,
    quantite_litres NUMERIC(10,2) NOT NULL DEFAULT 0,
    valeur_stock    NUMERIC(12,2) NOT NULL DEFAULT 0,
    cump_actuel     NUMERIC(10,2) NOT NULL DEFAULT 0,
    derniere_maj    TIMESTAMP NOT NULL DEFAULT NOW(),
    seuil_alerte    NUMERIC(10,2) DEFAULT 10
);

-- Historique de chaque entrée de miel brut
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

-- ============================================================================
-- 5. PRODUCTION & PRODUITS FINIS
-- ============================================================================

-- Types de bocaux : 10cl / 25cl / 50cl
CREATE TABLE types_bocaux (
    id            SERIAL PRIMARY KEY,
    nom           VARCHAR(20) NOT NULL,
    volume_litres NUMERIC(4,2) NOT NULL,
    cible         VARCHAR(50),
    prix_vente    NUMERIC(10,2)
);

-- Historique des transformations (mise en bocal)
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

-- Détail de chaque transformation par type de bocal
CREATE TABLE transformations_detail (
    id                SERIAL PRIMARY KEY,
    transformation_id INTEGER REFERENCES transformations(id) ON DELETE CASCADE,
    type_bocal_id     INTEGER REFERENCES types_bocaux(id),
    quantite_produite INTEGER NOT NULL
);

-- État courant du stock de bocaux prêts à vendre
CREATE TABLE stock_produit_fini (
    type_bocal_id       INTEGER PRIMARY KEY REFERENCES types_bocaux(id),
    quantite_disponible INTEGER NOT NULL DEFAULT 0,
    seuil_alerte        INTEGER DEFAULT 20
);

-- ============================================================================
-- 6. COMMERCIALISATION — SUPERMARCHÉS & SORTIES
-- ============================================================================

-- Supermarchés partenaires (seuls clients en gros autorisés)
CREATE TABLE supermarches (
    id          SERIAL PRIMARY KEY,
    nom         VARCHAR(150) NOT NULL,
    contact     VARCHAR(150),
    telephone   VARCHAR(50),
    email       VARCHAR(150),
    localisation VARCHAR(150),
    actif       BOOLEAN DEFAULT TRUE
);

-- Historique des ventes (sorties de bocaux aux supermarchés)
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

-- ============================================================================
-- 7. LOGISTIQUE (tables conservées pour les modules RH/Livraisons)
-- ============================================================================
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

CREATE TABLE livreurs (
    id         SERIAL PRIMARY KEY,
    nom        VARCHAR(150),
    telephone  VARCHAR(50),
    vehicule   VARCHAR(100),
    disponible BOOLEAN DEFAULT TRUE
);

CREATE TABLE livraisons (
    id                SERIAL PRIMARY KEY,
    vente_id          INTEGER REFERENCES ventes(id),
    livreur_id        INTEGER REFERENCES livreurs(id),
    date_prevue       TIMESTAMP,
    date_effective    TIMESTAMP,
    adresse_livraison TEXT,
    statut            VARCHAR(50) DEFAULT 'EN_ATTENTE'
);

CREATE TABLE disponibilites_livreurs (
    id         SERIAL PRIMARY KEY,
    livreur_id INTEGER REFERENCES livreurs(id),
    date_debut TIMESTAMP,
    date_fin   TIMESTAMP
);

-- ============================================================================
-- 8. COMPTABILITÉ & TRÉSORERIE
-- ============================================================================
CREATE TABLE comptes_tresorerie (
    id    SERIAL PRIMARY KEY,
    nom   VARCHAR(100),
    solde NUMERIC(12,2) DEFAULT 0
);

CREATE TABLE mouvements_financiers (
    id               SERIAL PRIMARY KEY,
    compte_id        INTEGER REFERENCES comptes_tresorerie(id),
    type             VARCHAR(20) NOT NULL,        -- 'recette' | 'depense'
    categorie        VARCHAR(100),
    montant          NUMERIC(12,2) NOT NULL,
    description      TEXT,
    date_transaction TIMESTAMP DEFAULT NOW(),
    created_at       TIMESTAMP DEFAULT NOW(),
    updated_at       TIMESTAMP DEFAULT NOW()
);

-- Vue de compatibilité pour les requêtes héritées
CREATE OR REPLACE VIEW transactions AS
SELECT id, compte_id, type, categorie, montant, description,
       date_transaction, created_at, updated_at
FROM mouvements_financiers;

-- ============================================================================
-- 9. DONNÉES FIXES D'INITIALISATION
-- ============================================================================

INSERT INTO roles (nom) VALUES
('ADMIN'), ('COMPTABLE'), ('MAGASINIER'), ('LIVREUR'), ('RESPONSABLE');

-- Compte admin par défaut (mot de passe à changer au premier login)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id)
VALUES ('Admin', 'Arovia', 'admin@arovia.com', '$2y$10$changeme', 1);

INSERT INTO comptes_tresorerie (nom, solde) VALUES
('CAISSE', 0), ('BNI', 0), ('MVOLA', 0), ('ORANGE MONEY', 0);

INSERT INTO statut (nom) VALUES
('En cours'), ('Signé'), ('Expiré'), ('Annulé');

-- Les 3 variantes de bocaux Arovia
INSERT INTO types_bocaux (nom, volume_litres, cible, prix_vente) VALUES
('10cl', 0.10, 'supermarche', NULL),
('25cl', 0.25, 'supermarche', NULL),
('50cl', 0.50, 'supermarche', NULL);

-- Stock produit fini initialisé à 0 pour chaque bocal
INSERT INTO stock_produit_fini (type_bocal_id, quantite_disponible, seuil_alerte)
SELECT id, 0, 20 FROM types_bocaux;

-- État initial du stock matière première (vide)
INSERT INTO stock_matiere_premiere (quantite_litres, valeur_stock, cump_actuel, seuil_alerte)
VALUES (0, 0, 0, 10);

INSERT INTO clients (nom, type_client, telephone, email, adresse) VALUES
('Jumbo Score Ankorondrano', 'supermarche', '+261 20 22 444 55', 'achats@jumbo.mg', 'Ankorondrano, Tana'),
('Leader Price Ivandry', 'supermarche', '+261 20 22 155 88', 'stock@leaderprice.mg', 'Ivandry, Tana'),
('Shoprite Antsirabe', 'supermarche', '+261 20 44 321 00', 'shoprite.ant@mg.mg', 'Centre-ville Antsirabe'),
('Score Behoririka', 'supermarche', '+261 20 22 987 65', 'behoririka@jumbo.mg', 'Behoririka, Tana');

INSERT INTO ventes (client_id, date_vente, montant_total, mode_paiement, statut) VALUES
(1, '2025-02-25 10:00:00', 1500000.00, 'Virement', 'PAYE'),
(2, '2025-03-15 09:30:00', 5000000.00, 'Virement', 'PAYE'),
(3, '2025-03-20 14:00:00', 6000000.00, 'Virement', 'PAYE'),
(4, '2025-04-02 11:15:00', 2750000.00, 'Cash', 'EN_COURS');