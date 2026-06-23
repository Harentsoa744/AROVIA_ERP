gestion_miel

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


-- la ligne "état du stock" unique qui sera mise à jour à chaque mouvement
INSERT INTO stock_matiere_premiere (quantite_litres, valeur_stock, cump_actuel)
VALUES (0, 0, 0);

-- tes 3 variantes de bocaux
INSERT INTO types_bocaux (nom, volume_litres, cible, prix_vente) VALUES
('10cl', 0.10, 'hotel', NULL),
('25cl', 0.25, 'particulier', NULL),
('50cl', 0.50, 'touriste', NULL);

-- initialise le stock produit fini à 0 pour chaque type
INSERT INTO stock_produit_fini (type_bocal_id, quantite_disponible)
SELECT id, 0 FROM types_bocaux;

CREATE TABLE sorties (
    id SERIAL PRIMARY KEY,
    date_sortie TIMESTAMP NOT NULL DEFAULT NOW(),
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite INTEGER NOT NULL,
    destinataire_type VARCHAR(20) NOT NULL,   -- 'touriste', 'particulier', 'hotel'
    destinataire_nom VARCHAR(150),
    prix_vente_unitaire NUMERIC(10,2) NOT NULL,
    valeur_totale NUMERIC(12,2) NOT NULL
);

UPDATE types_bocaux SET prix_vente = [nouveau_prix] WHERE nom = '10cl';
UPDATE types_bocaux SET prix_vente = [nouveau_prix] WHERE nom = '25cl';
UPDATE types_bocaux SET prix_vente = [nouveau_prix] WHERE nom = '50cl';