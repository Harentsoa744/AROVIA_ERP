-- ============================================================================
-- FICHIER : donnees.sql (v2 — Données de test complètes pour ERP Arovia)
-- PROJET  : ERP Miel Arovia
-- USAGE   : psql -U postgres -d gestion_miel -h 127.0.0.1 -f donnees.sql
-- ============================================================================

-- ============================================================================
-- 1. DONNÉES DE BASE / RH
-- ============================================================================

-- Entreprises pour contrats
INSERT INTO entreprise (nom, telephone, email) VALUES
('Hôtel des Baobabs',         '+261 34 11 222 33', 'contact@baobab-hotel.mg'),
('Supermarché Jumbo Score',   '+261 20 22 444 55', 'achats@jumbo.mg'),
('Distillerie du Vakinankaratra', '+261 32 07 888 99', 'prod@divak.mg'),
('Centrale d''Achat Malagasy','+261 33 15 777 11', 'cam@moov.mg');

-- Utilisateurs supplémentaires
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id) VALUES
('Raza',   'Andry', 'compta@arovia.com',  '$2y$10$changeme', 2),  -- COMPTABLE
('Rakoto', 'Jean',  'magasin@arovia.com', '$2y$10$changeme', 3),  -- MAGASINIER
('Randria','Marc',  'livreur1@arovia.com','$2y$10$changeme', 4);  -- LIVREUR

-- Employés
INSERT INTO employes (matricule, nom, prenom, telephone, email, adresse, poste, salaire_base, date_embauche, statut) VALUES
('EMP-001','Raza',   'Andry',  '+261 34 55 111 22','andry@arovia.com',  'Ambohitrarahaba, Tana','Comptable',        1200000.00,'2025-01-10','ACTIF'),
('EMP-002','Rakoto', 'Jean',   '+261 32 44 222 33','jean@arovia.com',   'Analamahitsy, Tana',  'Magasinier',        800000.00,'2025-01-15','ACTIF'),
('EMP-003','Randria','Marc',   '+261 33 66 333 44','marc@arovia.com',   'Itaosy, Tana',        'Chauffeur Livreur', 750000.00,'2025-02-01','ACTIF'),
('EMP-004','Ravalo', 'Sitraka','+261 34 77 444 55','sitraka@arovia.com','67Ha, Tana',          'Ouvrier Spécialisé',700000.00,'2025-02-15','ACTIF');

-- Contrats
INSERT INTO contrats (sujet, entreprise_id, statut_id, description, date_signature, date_expiration) VALUES
('Fourniture exclusive bocaux 10cl chambres',      1, 2, 'Contrat cadre 500 pièces/an',      '2025-02-01','2026-02-01'),
('Distribution bocaux 25cl et 50cl Grand Public',  2, 2, 'Référencement Tana',                '2025-03-15','2026-03-15'),
('Partenariat export Miel de Madagascar',          4, 1, 'Négociation marché régional',        '2026-02-10','2027-02-10');

-- Planning des employés
INSERT INTO planning (employe_id, date_debut, date_fin, type_evenement, description) VALUES
(1,'2025-03-05 08:00:00','2025-03-05 17:00:00','FORMATION','Formation nouveau module fiscal ERP'),
(4,'2025-03-12 08:00:00','2025-03-14 17:00:00','CONGE',    'Congé exceptionnel pour événement familial');

-- Paiement salaires
INSERT INTO paiements_salaires (employe_id, mois, annee, salaire_base, primes, deductions, montant_paye, date_paiement, commentaire) VALUES
(1, 2, 2025, 1200000.00, 50000.00,  0.00, 1250000.00,'2025-02-28 16:00:00','Salaire Février + Prime performance'),
(2, 2, 2025,  800000.00,     0.00,20000.00,  780000.00,'2025-02-28 16:05:00','Salaire Février - Avance sur salaire'),
(3, 2, 2025,  750000.00, 30000.00,  0.00,  780000.00,'2025-02-28 16:10:00','Salaire Février + Prime carburant');

-- ============================================================================
-- 2. FOURNISSEURS & ENTRÉES MATIÈRE PREMIÈRE (MIEL BRUT)
-- ============================================================================
INSERT INTO fournisseurs (nom, contact, telephone, email, localisation) VALUES
('Coopérative Apicole d''Antsirabe','Rabe Jean','+261 34 88 999 00','coop.antsirabe@gmail.com','Antsirabe'),
('Apiculteur Solo Mananara',        'Solo',     '+261 32 11 000 11','solo.miel@moov.mg',       'Mananara Nord'),
('Les Ruches du Sud',               'Mme Lala', '+261 33 22 111 22','ruches.sud@yahoo.fr',     'Fianarantsoa');

-- Entrées de miel brut (avec mise à jour du CUMP)
INSERT INTO entrees_matiere_premiere (fournisseur_id, numero_lot, date_entree, quantite_litres, prix_unitaire, valeur_totale, cump_apres_entree) VALUES
(1, 'MP-2025-0001', '2025-01-20 09:00:00', 500.00, 12000.00, 6000000.00, 12000.00),
(2, 'MP-2025-0002', '2025-02-15 14:30:00', 300.00, 13000.00, 3900000.00, 12375.00),
(3, 'MP-2025-0003', '2025-03-01 10:15:00', 1000.00, 11500.00, 11500000.00, 11888.89);

-- Mise à jour du stock matière première après les 3 entrées
UPDATE stock_matiere_premiere
SET quantite_litres = 1800.00,
    valeur_stock    = 21400000.00,
    cump_actuel     = 11888.89,
    derniere_maj    = NOW()
WHERE id = 1;

-- ============================================================================
-- 3. PRODUCTION & STOCK PRODUIT FINI (TRANSFORMATIONS)
-- ============================================================================

-- Transformation 1 : 200 L de miel de la Coop d'Antsirabe (ID 1)
INSERT INTO transformations (date_transformation, quantite_litres_utilisee, cump_applique, valeur_sortie, fournisseur_id, date_production, date_limite_vente, duree_conservation_mois) VALUES
('2025-02-20 08:00:00', 200.00, 12375.00, 2475000.00, 1, '2025-02-20', '2027-02-20', 24);

INSERT INTO transformations_detail (transformation_id, type_bocal_id, quantite_produite) VALUES
(1, 1, 500),  -- 500 bocaux de 10cl = 50L
(1, 2, 400),  -- 400 bocaux de 25cl = 100L
(1, 3, 100);  -- 100 bocaux de 50cl = 50L

-- Transformation 2 : 500 L de miel de l'Apiculteur Solo (ID 2)
INSERT INTO transformations (date_transformation, quantite_litres_utilisee, cump_applique, valeur_sortie, fournisseur_id, date_production, date_limite_vente, duree_conservation_mois) VALUES
('2025-03-10 11:00:00', 500.00, 11888.89, 5944445.00, 2, '2025-03-10', '2027-03-10', 24);

INSERT INTO transformations_detail (transformation_id, type_bocal_id, quantite_produite) VALUES
(2, 2, 400),  -- 400 bocaux de 25cl = 100L
(2, 3, 800);  -- 800 bocaux de 50cl = 400L

-- Mise à jour des stocks de produits finis disponibles après production
UPDATE stock_produit_fini SET quantite_disponible = 500 WHERE type_bocal_id = 1;
UPDATE stock_produit_fini SET quantite_disponible = 800 WHERE type_bocal_id = 2;
UPDATE stock_produit_fini SET quantite_disponible = 900 WHERE type_bocal_id = 3;

-- Mise à jour du stock MP réel restant : 1800 - 700 = 1100 L
UPDATE stock_matiere_premiere
SET quantite_litres = 1100.00,
    valeur_stock    = 1100.00 * 11888.89,
    derniere_maj    = NOW()
WHERE id = 1;

-- ============================================================================
-- 4. PARTENAIRES & COMMERCIALISATION (SUPERMARCHÉS & VENTES)
-- ============================================================================
INSERT INTO supermarches (nom, contact, telephone, email, localisation, actif) VALUES
('Jumbo Score Ankorondrano', 'M. Rajoelina',  '+261 20 22 444 55', 'achats@jumbo.mg',      'Ankorondrano, Tana',    TRUE),
('Leader Price Ivandry',    'Mme Rakoto',     '+261 20 22 155 88', 'stock@leaderprice.mg', 'Ivandry, Tana',         TRUE),
('Shoprite Antsirabe',      'M. Andrianah',   '+261 20 44 321 00', 'shoprite.ant@mg.mg',   'Centre-ville Antsirabe',TRUE),
('Score Behoririka',        'Mme Volah',      '+261 20 22 987 65', 'behoririka@jumbo.mg',  'Behoririka, Tana',      TRUE);

-- Ventes aux supermarchés (Sorties)
-- Vente 1 : Jumbo Score — 100 bocaux de 10cl (CUMP de la MP utilisé pour le bocal 10cl = 12 375 * 0.10 = 1 237.50 Ar/bocal)
INSERT INTO sorties (date_sortie, supermarche_id, type_bocal_id, quantite, prix_vente_unitaire, valeur_totale, cump_applique, marge_unitaire, marge_totale, motif) VALUES
('2025-02-25 10:00:00', 1, 1, 100, 15000.00, 1500000.00, 1237.50, 13762.50, 1376250.00, 'Vente');

-- Vente 2 : Leader Price — 200 bocaux de 25cl (CUMP de la MP = 11888.89 * 0.25 = 2972.22 Ar/bocal)
INSERT INTO sorties (date_sortie, supermarche_id, type_bocal_id, quantite, prix_vente_unitaire, valeur_totale, cump_applique, marge_unitaire, marge_totale, motif) VALUES
('2025-03-15 09:30:00', 2, 2, 200, 25000.00, 5000000.00, 2972.22, 22027.78, 4405556.00, 'Vente');

-- Vente 3 : Shoprite — 150 bocaux de 50cl (CUMP de la MP = 11888.89 * 0.50 = 5944.45 Ar/bocal)
INSERT INTO sorties (date_sortie, supermarche_id, type_bocal_id, quantite, prix_vente_unitaire, valeur_totale, cump_applique, marge_unitaire, marge_totale, motif) VALUES
('2025-03-20 14:00:00', 3, 3, 150, 40000.00, 6000000.00, 5944.45, 34055.55, 5108332.50, 'Vente');

-- Mise à jour du stock produit fini après ventes
UPDATE stock_produit_fini SET quantite_disponible = 500 - 100 WHERE type_bocal_id = 1;
UPDATE stock_produit_fini SET quantite_disponible = 800 - 200 WHERE type_bocal_id = 2;
UPDATE stock_produit_fini SET quantite_disponible = 900 - 150 WHERE type_bocal_id = 3;

-- ============================================================================
-- 5. COMPTABILITÉ & TRÉSORERIE (ALIMENTATION DES COMPTES & MOUVEMENTS)
-- ============================================================================
UPDATE comptes_tresorerie SET solde = 1500000.00  WHERE id = 1; -- CAISSE
UPDATE comptes_tresorerie SET solde = 25000000.00 WHERE id = 2; -- BNI
UPDATE comptes_tresorerie SET solde = 4500000.00  WHERE id = 3; -- MVOLA

-- Achats matières premières (dépenses)
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction) VALUES
(2,'depense','Achat Matière Première', 6000000.00, 'Paiement Lot MP-2025-0001 — Coop Antsirabe',       '2025-01-20 09:30:00'),
(3,'depense','Achat Matière Première', 3900000.00, 'Paiement Mobile Lot MP-2025-0002 — Solo',           '2025-02-15 15:00:00'),
(2,'depense','Achat Matière Première',11500000.00, 'Virement Lot MP-2025-0003 — Ruches du Sud',         '2025-03-01 11:00:00');

-- Salaires (dépenses)
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction) VALUES
(2,'depense','Salaires & Charges',1250000.00,'Salaire Février Andry Raza',  '2025-02-28 16:00:00'),
(2,'depense','Salaires & Charges', 780000.00,'Salaire Février Jean Rakoto', '2025-02-28 16:05:00'),
(2,'depense','Salaires & Charges', 780000.00,'Salaire Février Marc Randria','2025-02-28 16:10:00');

-- Recettes ventes supermarchés (recettes)
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction) VALUES
(2,'recette','Ventes Produits Finis',1500000.00,'Encaissement Vente bocaux 10cl — Jumbo Score', '2025-02-25 10:30:00'),
(2,'recette','Ventes Produits Finis',5000000.00,'Encaissement Vente bocaux 25cl — Leader Price','2025-03-15 10:00:00'),
(2,'recette','Ventes Produits Finis',6000000.00,'Encaissement Vente bocaux 50cl — Shoprite',    '2025-03-20 14:30:00');

-- Frais généraux (dépenses)
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction) VALUES
(1,'depense','Frais Généraux', 45000.00,'Achat fournitures bureau et étiquettes','2025-03-02 14:20:00');

-- Recalcul des soldes finaux
UPDATE comptes_tresorerie SET solde = 1500000.00 - 45000.00 WHERE id = 1; -- CAISSE : 1 455 000 Ar
UPDATE comptes_tresorerie SET solde = 25000000.00 - 6000000.00 - 11500000.00 - 1250000.00 - 780000.00 - 780000.00 + 1500000.00 + 5000000.00 + 6000000.00 WHERE id = 2; -- BNI : 16 190 000 Ar
UPDATE comptes_tresorerie SET solde = 4500000.00 - 3900000.00 WHERE id = 3; -- MVOLA : 600 000 Ar