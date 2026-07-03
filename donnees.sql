INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id) VALUES
('Raza', 'Lova', 'compta@arovia.com', '$2y$10$encryptedpasswordhere', 2),
('Rakoto', 'Jean', 'magasinier@arovia.com', '$2y$10$encryptedpasswordhere', 3),
('Randria', 'Michel', 'livreur1@arovia.com', '$2y$10$encryptedpasswordhere', 4);

INSERT INTO employes (matricule, nom, prenom, telephone, email, adresse, poste, salaire_base, date_embauche, statut) VALUES
('EMP-001', 'Rakoto', 'Jean', '+261 34 00 111 22', 'magasinier@arovia.com', 'Lot II M 45 Antananarivo', 'Magasinier', 600000.00, '2025-01-15', 'ACTIF'),
('EMP-002', 'Raza', 'Lova', '+261 32 00 333 44', 'compta@arovia.com', 'Lot IV G 12 Antananarivo', 'Comptable', 900000.00, '2025-02-01', 'ACTIF'),
('EMP-003', 'Randria', 'Michel', '+261 33 00 555 66', 'livreur1@arovia.com', 'Lot Tsiadana Ankatso', 'Livreur', 500000.00, '2025-03-01', 'ACTIF');

INSERT INTO paiements_salaires (employe_id, mois, annee, salaire_base, primes, deductions, montant_paye, date_paiement, commentaire) VALUES
(1, 5, 2026, 600000.00, 20000.00, 0.00, 620000.00, '2026-05-31 16:00:00', 'Salaire Mai + Prime assiduité'),
(2, 5, 2026, 900000.00, 0.00, 50000.00, 850000.00, '2026-05-31 16:05:00', 'Salaire Mai - Avance sur salaire');

INSERT INTO planning (employe_id, date_debut, date_fin, type_evenement, description) VALUES
(1, '2026-07-06 08:00:00', '2026-07-06 17:00:00', 'PRODUCTION', 'Mise en bocal du lot de miel de forêt'),
(3, '2026-07-07 09:00:00', '2026-07-07 16:00:00', 'LIVRAISON', 'Tournée des hôtels d''Ivalo');

INSERT INTO contrats (sujet, entreprise, contact, email, telephone, description, date_signature, date_expiration, statut) VALUES
('Fourniture exclusive de miel de litchi', 'Apiculteurs du Sud', 'M. Tsiry', 'tsiry@apisud.mg', '+261 34 55 666 77', 'Contrat d''approvisionnement de 2000L par an', '2026-01-10', '2027-01-10', 'SIGNED');

-- ============================================================================
-- 2. ACHATS & STOCK MATIÈRE PREMIÈRE
-- ============================================================================
INSERT INTO fournisseurs (nom, contact, telephone, email, localisation) VALUES
('Apiculteurs du Sud', 'M. Tsiry', '+261 34 55 666 77', 'tsiry@apisud.mg', 'Fianarantsoa'),
('Miel de l''Emyrne', 'Mme Voahangy', '+261 32 77 888 99', 'voahangy@emyrne.mg', 'Antananarivo Banlieue');

-- Simulation de deux entrées de matières premières (Miel brut)
-- Lot 1 : 500 Litres à 12 000 Ar/L
INSERT INTO entrees_matiere_premiere (fournisseur_id, numero_lot, date_entree, quantite_litres, prix_unitaire, valeur_totale, cump_apres_entree) VALUES
(1, 'LOT-202605-01', '2026-05-10 10:00:00', 500.00, 12000.00, 6000000.00, 12000.00);

-- Lot 2 : 300 Litres à 14 000 Ar/L (Mise à jour fictive du CUMP à 12 750 Ar/L)
INSERT INTO entrees_matiere_premiere (fournisseur_id, numero_lot, date_entree, quantite_litres, prix_unitaire, valeur_totale, cump_apres_entree) VALUES
(2, 'LOT-202606-02', '2026-06-15 14:30:00', 300.00, 14000.00, 4200000.00, 12750.00);

-- Mise à jour de la table de stock global (après les deux entrées)
UPDATE stock_matiere_premiere 
SET quantite_litres = 800.00, valeur_stock = 10200000.00, cump_actuel = 12750.00, derniere_maj = NOW()
WHERE id = 1;

-- ============================================================================
-- 3. PRODUCTION & STOCK PRODUITS FINIS
-- ============================================================================
-- Les types de bocaux existent déjà (id 1 à 5 grâce à votre script)
-- On transforme 100 Litres de miel brut (CUMP à 12750 -> Valeur = 1 275 000 Ar)
INSERT INTO transformations (date_transformation, quantite_litres_utilisee, cump_applique, valeur_sortie) VALUES
('2026-06-20 09:00:00', 100.00, 12750.00, 1275000.00);

-- Répartition de la transformation :
-- 100L = (100 bocaux de 50cl = 50L) + (50 bocaux de 1L = 50L)
INSERT INTO transformations_detail (transformation_id, type_bocal_id, quantite_produite) VALUES
(1, 3, 100), -- 100 bocaux de 50cl
(1, 4, 50);  -- 50 bocaux de 1L

-- Mise à jour du stock de matière première restant (800L - 100L = 700L)
UPDATE stock_matiere_premiere 
SET quantite_litres = 700.00, valeur_stock = 8925000.00 
WHERE id = 1;

-- Remplissage du stock de produits finis correspondant
UPDATE stock_produit_fini SET quantite_disponible = 100 WHERE type_bocal_id = 3;
UPDATE stock_produit_fini SET quantite_disponible = 50 WHERE type_bocal_id = 4;

-- ============================================================================
-- 4. COMMERCIALISATION & LOGISTIQUE
-- ============================================================================
INSERT INTO clients (nom, type_client, telephone, email, adresse) VALUES
('Hôtel des Avenues', 'hotel', '+261 20 22 111 00', 'contact@hotelavenues.mg', 'Analakely, Antananarivo'),
('Mme Rakotomalala Rova', 'particulier', '+261 34 88 999 00', 'rova@gmail.com', 'Analamahitsy, Antananarivo');

-- Vente 1 : 10 bocaux de 50cl (à 40 000 Ar) et 5 bocaux de 1L (à 70 000 Ar) = 750 000 Ar
INSERT INTO ventes (client_id, date_vente, montant_total, mode_paiement, statut) VALUES
(1, '2026-06-22 11:00:00', 750000.00, 'CHÈQUE', 'PAYE');

INSERT INTO vente_details (vente_id, type_bocal_id, quantite, prix_unitaire, total_ligne) VALUES
(1, 3, 10, 40000.00, 400000.00),
(1, 4, 5, 70000.00, 350000.00);

-- Vente 2 : En attente de paiement
INSERT INTO ventes (client_id, date_vente, montant_total, mode_paiement, statut) VALUES
(2, '2026-06-25 15:00:00', 150000.00, 'MVOLA', 'EN_ATTENTE');

INSERT INTO vente_details (vente_id, type_bocal_id, quantite, prix_unitaire, total_ligne) VALUES
(2, 3, 2, 40000.00, 80000.00),
(2, 4, 1, 70000.00, 70000.00);

-- Sortie hors-vente (ex: Perte ou Don)
-- Cas : 1 bocal de 1L cassé dans le dépôt
INSERT INTO sorties (date_sortie, type_bocal_id, quantite, motif, commentaire, destinataire_type, destinataire_nom, prix_vente_unitaire, valeur_totale) VALUES
('2026-06-23 16:00:00', 4, 1, 'Perte', 'Bocal cassé durant l''inventaire', NULL, NULL, 0.00, 0.00);

-- Ajustement du stock produit fini après Ventes et Sorties :
-- Bocaux 50cl (id 3) : 100 produits - 10 vendus - 2 vendus = 88 restants
-- Bocaux 1L (id 4) : 50 produits - 5 vendus - 1 vendu - 1 cassé = 43 restants
UPDATE stock_produit_fini SET quantite_disponible = 88 WHERE type_bocal_id = 3;
UPDATE stock_produit_fini SET quantite_disponible = 43 WHERE type_bocal_id = 4;

-- Logistique / Livraisons
INSERT INTO livreurs (nom, telephone, vehicule, disponible) VALUES
('Randria Michel', '+261 33 00 555 66', 'Scooter Honda Lead', TRUE),
('Andry Transports', '+261 32 44 555 11', 'Fourgonnette Renault Kangoo', TRUE);

INSERT INTO livraisons (vente_id, livreur_id, date_prevue, date_effective, adresse_livraison, statut) VALUES
(1, 1, '2026-06-23 10:00:00', '2026-06-23 09:45:00', 'Analakely, Antananarivo', 'LIVRE'),
(2, 2, '2026-06-26 14:00:00', NULL, 'Analamahitsy, Antananarivo', 'EN_COURS');

INSERT INTO disponibilites_livreurs (livreur_id, date_debut, date_fin) VALUES
(1, '2026-07-01 08:00:00', '2026-07-03 18:00:00');

-- ============================================================================
-- 5. COMPTABILITÉ & TRÉSORERIE
-- ============================================================================
-- Rappel des comptes créés par votre script : CAISSE (1), BNI (2), MVOLA (3), ORANGE MONEY (4)

-- Injection de fonds de départ ou mouvements
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction) VALUES
(2, 'recette', 'Apport initial', 15000000.00, 'Dépôt de capital initial à la banque BNI', '2026-05-01 08:00:00'),
(2, 'depense', 'Achat Matière Première', 6000000.00, 'Paiement Lot LOT-202605-01 à Apiculteurs du Sud', '2026-05-10 11:00:00'),
(2, 'depense', 'Achat Matière Première', 4200000.00, 'Paiement Lot LOT-202606-02 à Miel de l''Emyrne', '2026-06-15 15:00:00'),
(2, 'recette', 'Ventes', 750000.00, 'Chèque Vente N°1 - Hôtel des Avenues', '2026-06-22 11:30:00'),
(1, 'depense', 'RH / Salaires', 620000.00, 'Salaire Mai - Rakoto Jean', '2026-05-31 16:00:00'),
(1, 'depense', 'RH / Salaires', 850000.00, 'Salaire Mai - Raza Lova', '2026-05-31 16:05:00');

-- Mise à jour manuelle des soldes des comptes de trésorerie pour correspondre aux mouvements
-- CAISSE : 0 - 620 000 - 850 000 = -1 470 000 Ar (à renflouer par la BNI en situation réelle)
-- BNI : 15 000 000 - 6 000 000 - 4 200 000 + 750 000 = 5 550 000 Ar
UPDATE comptes_tresorerie SET solde = -1470000.00 WHERE id = 1;
UPDATE comptes_tresorerie SET solde = 5550000.00 WHERE id = 2;