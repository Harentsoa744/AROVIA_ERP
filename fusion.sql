DROP TABLE IF EXISTS disponibilites_livreurs CASCADE;
DROP TABLE IF EXISTS livraisons CASCADE;
DROP TABLE IF EXISTS livreurs CASCADE;
DROP TABLE IF EXISTS vente_details CASCADE;
DROP TABLE IF EXISTS ventes CASCADE;
DROP TABLE IF EXISTS clients CASCADE;
DROP TABLE IF EXISTS sorties CASCADE;
DROP TABLE IF EXISTS stock_produit_fini CASCADE;
DROP TABLE IF EXISTS transformations_detail CASCADE;
DROP TABLE IF EXISTS transformations CASCADE;
DROP TABLE IF EXISTS types_bocaux CASCADE;
DROP TABLE IF EXISTS entrees_matiere_premiere CASCADE;
DROP TABLE IF EXISTS stock_matiere_premiere CASCADE;
DROP TABLE IF EXISTS fournisseurs CASCADE;
DROP TABLE IF EXISTS mouvements_financiers CASCADE;
DROP TABLE IF EXISTS comptes_tresorerie CASCADE;
DROP TABLE IF EXISTS paiements_salaires CASCADE;
DROP TABLE IF EXISTS planning CASCADE;
DROP TABLE IF EXISTS employes CASCADE;
DROP TABLE IF EXISTS contrats CASCADE;
DROP TABLE IF EXISTS utilisateurs CASCADE;
DROP TABLE IF EXISTS roles CASCADE;

-- ============================================================================
-- 2. STRUCTURES DE GESTION DES UTILISATEURS & RH
-- ============================================================================
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe TEXT NOT NULL,
    role_id INTEGER REFERENCES roles(id),
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT NOW()
);

CREATE TABLE employes (
    id SERIAL PRIMARY KEY,
    matricule VARCHAR(30) UNIQUE,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    telephone VARCHAR(30),
    email VARCHAR(150),
    adresse TEXT,
    poste VARCHAR(100),
    salaire_base NUMERIC(12,2),
    date_embauche DATE,
    date_fin_contrat DATE,
    statut VARCHAR(30) DEFAULT 'ACTIF'
);

CREATE TABLE paiements_salaires (
    id SERIAL PRIMARY KEY,
    employe_id INTEGER REFERENCES employes(id),
    mois INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    salaire_base NUMERIC(12,2),
    primes NUMERIC(12,2) DEFAULT 0,
    deductions NUMERIC(12,2) DEFAULT 0,
    montant_paye NUMERIC(12,2) NOT NULL,
    date_paiement TIMESTAMP DEFAULT NOW(),
    commentaire TEXT
);

CREATE TABLE planning (
    id SERIAL PRIMARY KEY,
    employe_id INTEGER REFERENCES employes(id),
    date_debut TIMESTAMP,
    date_fin TIMESTAMP,
    type_evenement VARCHAR(50),
    description TEXT
);

CREATE TABLE contrats (
    id SERIAL PRIMARY KEY,
    sujet VARCHAR(200) NOT NULL,
    entreprise VARCHAR(150) NOT NULL,
    contact VARCHAR(150),
    email VARCHAR(150),
    telephone VARCHAR(50),
    description TEXT,
    date_creation TIMESTAMP DEFAULT NOW(),
    date_signature DATE,
    date_expiration DATE,
    statut VARCHAR(50) DEFAULT 'PENDING',
    fichier_contrat TEXT
);

-- ============================================================================
-- 3. STRUCTURES ACHATS & STOCK MATIÈRE PREMIÈRE (MIEL)
-- ============================================================================
CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    contact VARCHAR(150),
    telephone VARCHAR(50),
    email VARCHAR(150),
    localisation VARCHAR(150)
);

CREATE TABLE stock_matiere_premiere (
    id SERIAL PRIMARY KEY,
    quantite_litres NUMERIC(12,2) NOT NULL DEFAULT 0,
    valeur_stock NUMERIC(12,2) NOT NULL DEFAULT 0,
    cump_actuel NUMERIC(12,2) NOT NULL DEFAULT 0,
    derniere_maj TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE entrees_matiere_premiere (
    id SERIAL PRIMARY KEY,
    fournisseur_id INTEGER REFERENCES fournisseurs(id),
    numero_lot VARCHAR(50) UNIQUE,
    date_entree TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres NUMERIC(12,2) NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    valeur_totale NUMERIC(12,2) NOT NULL,
    cump_apres_entree NUMERIC(12,2) NOT NULL
);

-- ============================================================================
-- 4. PRODUCTION & PRODUITS FINIS
-- ============================================================================
CREATE TABLE types_bocaux (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(30) NOT NULL,
    volume_litres NUMERIC(5,2) NOT NULL,
    cible VARCHAR(50) NOT NULL,
    prix_vente NUMERIC(12,2)
);

CREATE TABLE transformations (
    id SERIAL PRIMARY KEY,
    date_transformation TIMESTAMP NOT NULL DEFAULT NOW(),
    quantite_litres_utilisee NUMERIC(12,2) NOT NULL,
    cump_applique NUMERIC(12,2) NOT NULL,
    valeur_sortie NUMERIC(12,2) NOT NULL
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

-- ============================================================================
-- 5. COMMERCIALISATION, SORTIES & LOGISTIQUE
-- ============================================================================
CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    type_client VARCHAR(50),
    telephone VARCHAR(50),
    email VARCHAR(150),
    adresse TEXT
);

CREATE TABLE ventes (
    id SERIAL PRIMARY KEY,
    client_id INTEGER REFERENCES clients(id),
    date_vente TIMESTAMP DEFAULT NOW(),
    montant_total NUMERIC(12,2),
    mode_paiement VARCHAR(50),
    statut VARCHAR(50) DEFAULT 'PAYE'
);

CREATE TABLE vente_details (
    id SERIAL PRIMARY KEY,
    vente_id INTEGER REFERENCES ventes(id) ON DELETE CASCADE,
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite INTEGER NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    total_ligne NUMERIC(12,2) NOT NULL
);

-- Table des sorties fusionnée
CREATE TABLE sorties (
    id SERIAL PRIMARY KEY,
    date_sortie TIMESTAMP NOT NULL DEFAULT NOW(),
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite INTEGER NOT NULL,
    motif VARCHAR(50),                         -- ex: 'Perte', 'Don', 'Vente Directe'
    commentaire TEXT,
    destinataire_type VARCHAR(50),             -- 'touriste', 'particulier', 'hotel'
    destinataire_nom VARCHAR(150),
    prix_vente_unitaire NUMERIC(12,2) DEFAULT 0,
    valeur_totale NUMERIC(12,2) DEFAULT 0
);

CREATE TABLE livreurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150),
    telephone VARCHAR(50),
    vehicule VARCHAR(100),
    disponible BOOLEAN DEFAULT TRUE
);

CREATE TABLE livraisons (
    id SERIAL PRIMARY KEY,
    vente_id INTEGER REFERENCES ventes(id),
    livreur_id INTEGER REFERENCES livreurs(id),
    date_prevue TIMESTAMP,
    date_effective TIMESTAMP,
    adresse_livraison TEXT,
    statut VARCHAR(50) DEFAULT 'EN_ATTENTE'
);

CREATE TABLE disponibilites_livreurs (
    id SERIAL PRIMARY KEY,
    livreur_id INTEGER REFERENCES livreurs(id),
    date_debut TIMESTAMP,
    date_fin TIMESTAMP
);

-- ============================================================================
-- 6. COMPTABILITÉ & TRÉSORERIE
-- ============================================================================
CREATE TABLE comptes_tresorerie (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    solde NUMERIC(12,2) DEFAULT 0
);

CREATE TABLE mouvements_financiers (
    id SERIAL PRIMARY KEY,
    compte_id INTEGER REFERENCES comptes_tresorerie(id),
    type VARCHAR(20) NOT NULL,                  -- 'recette' ou 'depense'
    categorie VARCHAR(100),
    montant NUMERIC(12,2) NOT NULL,
    description TEXT,
    date_transaction TIMESTAMP DEFAULT NOW(),   -- Requis par le Framework et filtres
    created_at TIMESTAMP DEFAULT NOW(),         -- Requis par $useTimestamps = true
    updated_at TIMESTAMP DEFAULT NOW()          -- Requis par $useTimestamps = true
);

-- Création d'une vue de secours "transactions" pour éviter les crashs 
-- sur les requêtes SQL codées en dur (comme getEvolutionMensuelle())
CREATE OR REPLACE VIEW transactions AS 
SELECT id, compte_id, type, categorie, montant, description, date_transaction, created_at, updated_at
FROM mouvements_financiers;

-- ============================================================================
-- 7. INITIALISATION DES DONNÉES (INSERTS)
-- ============================================================================
INSERT INTO stock_matiere_premiere (quantite_litres, valeur_stock, cump_actuel)
VALUES (0, 0, 0);

INSERT INTO roles (nom) VALUES
('ADMIN'), ('COMPTABLE'), ('MAGASINIER'), ('LIVREUR'), ('RESPONSABLE');

INSERT INTO comptes_tresorerie (nom, solde) VALUES
('CAISSE', 0), ('BNI', 0), ('MVOLA', 0), ('ORANGE MONEY', 0);

INSERT INTO types_bocaux (nom, volume_litres, cible, prix_vente) VALUES
('10cl', 0.10, 'hotel', 15000),
('25cl', 0.25, 'particulier', 25000),
('50cl', 0.50, 'touriste', 40000),
('1L',  1.00, 'famille', 70000),
('5L',  5.00, 'grossiste', 300000);

INSERT INTO stock_produit_fini (type_bocal_id, quantite_disponible)
SELECT id, 0 FROM types_bocaux;

INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id) VALUES
('Administrateur', 'AROVIA', 'admin@arovia.com', '$2y$10$changeme', 1);