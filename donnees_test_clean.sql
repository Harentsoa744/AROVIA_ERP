-- ============================================================================
-- FICHIER    : donnees_test_clean.sql
-- PROJET     : ERP Miel Arovia
-- BASE       : gestion_miel (MySQL)
-- USAGE      : Données de test réalistes pour l'application (sans conflits)
-- NOTE       : Exécuter APRES fusion.sql pour ajouter des données de test
-- ============================================================================

-- ============================================================================
-- 1. UTILISATEURS (données supplémentaires)
-- ============================================================================
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role_id, actif, date_creation) VALUES
('Comptable', 'Marie', 'comptable@arovia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, TRUE, '2026-01-05 09:30:00'),
('Magasinier', 'Paul', 'magasinier@arovia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, TRUE, '2026-01-10 10:00:00'),
('Livreur1', 'Marc', 'livreur1@arovia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, TRUE, '2026-01-15 11:00:00'),
('Livreur2', 'Luc', 'livreur2@arovia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, TRUE, '2026-01-20 12:00:00'),
('Responsable', 'Pierre', 'responsable@arovia.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, TRUE, '2026-01-25 13:00:00');

-- ============================================================================
-- 2. EMPLOYÉS
-- ============================================================================
INSERT INTO employes (matricule, nom, prenom, telephone, email, adresse, poste, salaire_base, date_embauche, statut) VALUES
('EMP001', 'Rakoto', 'Jean', '+261 34 00 123 45', 'jean.rakoto@arovia.com', 'Lot IV 123 Antanimena', 'Gérant', 1500000.00, '2024-01-15', 'ACTIF'),
('EMP002', 'Rasoa', 'Marie', '+261 34 00 234 56', 'marie.rasoa@arovia.com', 'Lot V 456 Isoraka', 'Comptable', 800000.00, '2024-02-01', 'ACTIF'),
('EMP003', 'Randria', 'Paul', '+261 34 00 345 67', 'paul.randria@arovia.com', 'Lot III 789 Ambanidia', 'Magasinier', 600000.00, '2024-03-10', 'ACTIF'),
('EMP004', 'Rabe', 'Marc', '+261 34 00 456 78', 'marc.rabe@arovia.com', 'Lot II 101 Tsimbazaza', 'Livreur', 500000.00, '2024-04-05', 'ACTIF'),
('EMP005', 'Andriamanitra', 'Luc', '+261 34 00 567 89', 'luc.andriamanitra@arovia.com', 'Lot I 202 Anosy', 'Livreur', 500000.00, '2024-05-20', 'ACTIF'),
('EMP006', 'Rakotondrabe', 'Pierre', '+261 34 00 678 90', 'pierre.rakotondrabe@arovia.com', 'Lot VI 303 Mahamasina', 'Responsable Production', 1200000.00, '2023-06-01', 'ACTIF'),
('EMP007', 'Rasendrasoa', 'Fleur', '+261 34 00 789 01', 'fleur.rasendrasoa@arovia.com', 'Lot VII 404 Ivato', 'Ouvrier', 450000.00, '2024-07-15', 'ACTIF'),
('EMP008', 'Rajaonarivelo', 'David', '+261 34 00 890 12', 'david.rajaonarivelo@arovia.com', 'Lot VIII 505 Ambohimanarina', 'Ouvrier', 450000.00, '2024-08-01', 'ACTIF'),
('EMP009', 'Ramanantsoa', 'Claudine', '+261 34 00 901 23', 'claudine.ramanantsoa@arovia.com', 'Lot IX 606 Antanimena', 'Secrétaire', 550000.00, '2024-09-10', 'ACTIF'),
('EMP010', 'Rakotomavo', 'Henri', '+261 34 00 012 34', 'henri.rakotomavo@arovia.com', 'Lot X 707 Behoririka', 'Chauffeur', 480000.00, '2024-10-05', 'ACTIF'),
('EMP011', 'Rasolofomanana', 'Jeanne', '+261 34 00 123 45', 'jeanne.rasolofomanana@arovia.com', 'Lot XI 808 Alarobia', 'Ouvrier', 420000.00, '2025-01-15', 'ACTIF'),
('EMP012', 'Rakotoarisoa', 'Christian', '+261 34 00 234 56', 'christian.rakotoarisoa@arovia.com', 'Lot XII 909 Anosizato', 'Technicien', 700000.00, '2025-02-20', 'ACTIF');

-- ============================================================================
-- 3. PAIEMENTS DE SALAIRES
-- ============================================================================
INSERT INTO paiements_salaires (employe_id, mois, annee, salaire_base, primes, deductions, montant_paye, date_paiement, commentaire) VALUES
-- Janvier 2026
(1, 1, 2026, 1500000.00, 100000.00, 50000.00, 1550000.00, '2026-01-30 15:00:00', 'Salaire janvier + prime'),
(2, 1, 2026, 800000.00, 50000.00, 20000.00, 830000.00, '2026-01-30 15:30:00', 'Salaire janvier'),
(3, 1, 2026, 600000.00, 30000.00, 15000.00, 615000.00, '2026-01-30 16:00:00', 'Salaire janvier'),
(4, 1, 2026, 500000.00, 25000.00, 10000.00, 515000.00, '2026-01-30 16:30:00', 'Salaire janvier'),
(5, 1, 2026, 500000.00, 25000.00, 10000.00, 515000.00, '2026-01-30 17:00:00', 'Salaire janvier'),
(6, 1, 2026, 1200000.00, 80000.00, 40000.00, 1240000.00, '2026-01-30 17:30:00', 'Salaire janvier + prime'),
(7, 1, 2026, 450000.00, 20000.00, 10000.00, 460000.00, '2026-01-30 18:00:00', 'Salaire janvier'),
(8, 1, 2026, 450000.00, 20000.00, 10000.00, 460000.00, '2026-01-30 18:30:00', 'Salaire janvier'),
(9, 1, 2026, 550000.00, 30000.00, 15000.00, 565000.00, '2026-01-30 19:00:00', 'Salaire janvier'),
(10, 1, 2026, 480000.00, 25000.00, 12000.00, 493000.00, '2026-01-30 19:30:00', 'Salaire janvier'),
-- Février 2026
(1, 2, 2026, 1500000.00, 150000.00, 60000.00, 1590000.00, '2026-02-28 15:00:00', 'Salaire février + prime exceptionnelle'),
(2, 2, 2026, 800000.00, 60000.00, 25000.00, 835000.00, '2026-02-28 15:30:00', 'Salaire février'),
(3, 2, 2026, 600000.00, 40000.00, 18000.00, 622000.00, '2026-02-28 16:00:00', 'Salaire février'),
(4, 2, 2026, 500000.00, 35000.00, 15000.00, 520000.00, '2026-02-28 16:30:00', 'Salaire février'),
(5, 2, 2026, 500000.00, 35000.00, 15000.00, 520000.00, '2026-02-28 17:00:00', 'Salaire février'),
(6, 2, 2026, 1200000.00, 100000.00, 50000.00, 1250000.00, '2026-02-28 17:30:00', 'Salaire février + prime'),
(7, 2, 2026, 450000.00, 25000.00, 12000.00, 463000.00, '2026-02-28 18:00:00', 'Salaire février'),
(8, 2, 2026, 450000.00, 25000.00, 12000.00, 463000.00, '2026-02-28 18:30:00', 'Salaire février'),
(9, 2, 2026, 550000.00, 35000.00, 18000.00, 567000.00, '2026-02-28 19:00:00', 'Salaire février'),
(10, 2, 2026, 480000.00, 30000.00, 14000.00, 496000.00, '2026-02-28 19:30:00', 'Salaire février'),
-- Mars 2026
(1, 3, 2026, 1500000.00, 120000.00, 55000.00, 1565000.00, '2026-03-31 15:00:00', 'Salaire mars'),
(2, 3, 2026, 800000.00, 55000.00, 22000.00, 833000.00, '2026-03-31 15:30:00', 'Salaire mars'),
(3, 3, 2026, 600000.00, 35000.00, 16000.00, 619000.00, '2026-03-31 16:00:00', 'Salaire mars'),
(4, 3, 2026, 500000.00, 30000.00, 12000.00, 518000.00, '2026-03-31 16:30:00', 'Salaire mars'),
(5, 3, 2026, 500000.00, 30000.00, 12000.00, 518000.00, '2026-03-31 17:00:00', 'Salaire mars'),
(6, 3, 2026, 1200000.00, 90000.00, 45000.00, 1245000.00, '2026-03-31 17:30:00', 'Salaire mars + prime'),
(7, 3, 2026, 450000.00, 22000.00, 11000.00, 461000.00, '2026-03-31 18:00:00', 'Salaire mars'),
(8, 3, 2026, 450000.00, 22000.00, 11000.00, 461000.00, '2026-03-31 18:30:00', 'Salaire mars'),
(9, 3, 2026, 550000.00, 32000.00, 16000.00, 566000.00, '2026-03-31 19:00:00', 'Salaire mars'),
(10, 3, 2026, 480000.00, 28000.00, 13000.00, 495000.00, '2026-03-31 19:30:00', 'Salaire mars');

-- ============================================================================
-- 4. PLANNING
-- ============================================================================
INSERT INTO planning (employe_id, date_debut, date_fin, type_evenement, description) VALUES
(1, '2026-07-15 08:00:00', '2026-07-15 17:00:00', 'Réunion', 'Réunion mensuelle avec l''équipe'),
(2, '2026-07-16 09:00:00', '2026-07-16 12:00:00', 'Formation', 'Formation comptabilité'),
(3, '2026-07-17 07:00:00', '2026-07-17 18:00:00', 'Inventaire', 'Inventaire stock produit fini'),
(4, '2026-07-18 08:00:00', '2026-07-18 18:00:00', 'Livraison', 'Livraison Jumbo Score'),
(5, '2026-07-19 08:00:00', '2026-07-19 18:00:00', 'Livraison', 'Livraison Leader Price'),
(6, '2026-07-20 08:00:00', '2026-07-20 17:00:00', 'Production', 'Production miel 25cl'),
(7, '2026-07-21 07:00:00', '2026-07-21 18:00:00', 'Maintenance', 'Maintenance machines'),
(8, '2026-07-22 07:00:00', '2026-07-22 18:00:00', 'Production', 'Production miel 50cl'),
(9, '2026-07-23 08:00:00', '2026-07-23 17:00:00', 'Réunion', 'Réunion équipe RH'),
(10, '2026-07-24 08:00:00', '2026-07-24 18:00:00', 'Livraison', 'Livraison Shoprite Antsirabe');

-- ============================================================================
-- 5. ENTREPRISES (données supplémentaires)
-- ============================================================================
INSERT INTO entreprise (nom, telephone, email) VALUES
('Mada Market', '+261 20 22 333 44', 'contact@madamarket.mg'),
('Super U Madagascar', '+261 20 22 777 88', 'contact@superu.mg'),
('Carrefour Madagascar', '+261 20 22 666 77', 'contact@carrefour.mg'),
('Acima Madagascar', '+261 20 22 555 66', 'contact@acima.mg');

-- ============================================================================
-- 6. STATUT (données supplémentaires)
-- ============================================================================
INSERT INTO statut (nom) VALUES
('En attente'), ('Résilié');

-- ============================================================================
-- 7. CONTRATS
-- ============================================================================
INSERT INTO contrats (sujet, entreprise_id, statut_id, description, date_signature, date_expiration, date_creation) VALUES
('Distribution miel 10cl', 1, 2, 'Contrat de distribution annuel pour les bocaux 10cl', '2025-01-15', '2026-01-15', '2025-01-15 10:00:00'),
('Distribution miel 25cl', 2, 2, 'Contrat de distribution annuel pour les bocaux 25cl', '2025-02-01', '2026-02-01', '2025-02-01 11:00:00'),
('Distribution miel 50cl', 3, 2, 'Contrat de distribution annuel pour les bocaux 50cl', '2025-03-01', '2026-03-01', '2025-03-01 12:00:00'),
('Approvisionnement miel brut', 1, 1, 'Contrat d''approvisionnement en miel brut', '2025-04-15', '2026-04-15', '2025-04-15 14:00:00'),
('Distribution mixte', 4, 2, 'Contrat de distribution mixte (10cl, 25cl, 50cl)', '2025-05-01', '2026-05-01', '2025-05-01 15:00:00'),
('Livraison exclusive', 5, 3, 'Contrat de livraison exclusive (expiré)', '2024-06-01', '2025-06-01', '2024-06-01 16:00:00'),
('Partenariat stratégique', 6, 1, 'Partenariat stratégique pour 2 ans', '2025-07-01', '2027-07-01', '2025-07-01 17:00:00'),
('Distribution régionale', 7, 2, 'Distribution dans les régions', '2025-08-01', '2026-08-01', '2025-08-01 18:00:00'),
('Approvisionnement mensuel', 8, 1, 'Approvisionnement mensuel en miel', '2025-09-01', '2026-09-01', '2025-09-01 19:00:00'),
('Contrat test', 1, 4, 'Contrat annulé (test)', '2025-10-01', '2026-10-01', '2025-10-01 20:00:00');

-- ============================================================================
-- 8. FOURNISSEURS
-- ============================================================================
INSERT INTO fournisseurs (nom, contact, telephone, email, localisation) VALUES
('Miel de Madagascar', 'Rakoto Jean', '+261 34 00 111 22', 'contact@miel-mada.mg', 'Antananarivo'),
('Ruche Dorée', 'Rasoa Marie', '+261 34 00 222 33', 'info@ruchedoree.mg', 'Antsirabe'),
('Apiculteur Union', 'Randria Paul', '+261 34 00 333 44', 'union@apiculteur.mg', 'Fianarantsoa'),
('Miel Pur', 'Rabe Marc', '+261 34 00 444 55', 'miel@mielpur.mg', 'Toamasina'),
('Nectar Mada', 'Andriamanitra Luc', '+261 34 00 555 66', 'nectar@nectarmada.mg', 'Mahajanga'),
('Abeille Royale', 'Rakotondrabe Pierre', '+261 34 00 666 77', 'royale@abeille.mg', 'Antsiranana'),
('Miel Bio', 'Rasendrasoa Fleur', '+261 34 00 777 88', 'bio@mielbio.mg', 'Toliara'),
('Ruche Traditionnelle', 'Rajaonarivelo David', '+261 34 00 888 99', 'tradition@ruche.mg', 'Ambatondrazaka'),
('Essence Miel', 'Ramanantsoa Claudine', '+261 34 00 999 00', 'essence@miel.mg', 'Morondava'),
('Douceur Mada', 'Rakotomavo Henri', '+261 34 00 000 11', 'douceur@douceur.mg', 'Manakara');

-- ============================================================================
-- 9. STOCK MATIÈRE PREMIÈRE (mise à jour)
-- ============================================================================
UPDATE stock_matiere_premiere SET quantite_litres = 450.50, valeur_stock = 13515000.00, cump_actuel = 30000.00, derniere_maj = '2026-07-13 10:00:00', seuil_alerte = 50.00 WHERE id = 1;

-- ============================================================================
-- 10. ENTRÉES MATIÈRE PREMIÈRE
-- ============================================================================
INSERT INTO entrees_matiere_premiere (fournisseur_id, numero_lot, date_entree, quantite_litres, prix_unitaire, valeur_totale, cump_apres_entree) VALUES
(1, 'LOT-2026-001', '2026-01-15 08:00:00', 100.00, 28000.00, 2800000.00, 28000.00),
(2, 'LOT-2026-002', '2026-02-10 09:00:00', 80.00, 29000.00, 2320000.00, 28444.44),
(3, 'LOT-2026-003', '2026-03-05 10:00:00', 120.00, 31000.00, 3720000.00, 29333.33),
(4, 'LOT-2026-004', '2026-04-20 11:00:00', 90.00, 30500.00, 2745000.00, 29600.00),
(5, 'LOT-2026-005', '2026-05-15 12:00:00', 110.00, 29500.00, 3245000.00, 29583.33),
(6, 'LOT-2026-006', '2026-06-10 13:00:00', 85.00, 30000.00, 2550000.00, 29666.67),
(7, 'LOT-2026-007', '2026-07-01 14:00:00', 95.00, 29800.00, 2831000.00, 29700.00),
(8, 'LOT-2026-008', '2026-07-10 15:00:00', 75.00, 30200.00, 2265000.00, 29800.00),
(9, 'LOT-2026-009', '2026-07-12 16:00:00', 60.00, 29900.00, 1794000.00, 29833.33),
(10, 'LOT-2026-010', '2026-07-13 08:00:00', 50.00, 30000.00, 1500000.00, 29850.00);

-- ============================================================================
-- 11. TRANSFORMATIONS
-- ============================================================================
INSERT INTO transformations (date_transformation, quantite_litres_utilisee, cump_applique, valeur_sortie, fournisseur_id, date_production, date_limite_vente, duree_conservation_mois) VALUES
('2026-01-20 08:00:00', 50.00, 28000.00, 1400000.00, 1, '2026-01-20', '2028-01-20', 24),
('2026-02-15 09:00:00', 40.00, 28444.44, 1137777.60, 2, '2026-02-15', '2028-02-15', 24),
('2026-03-10 10:00:00', 60.00, 29333.33, 1760000.00, 3, '2026-03-10', '2028-03-10', 24),
('2026-04-25 11:00:00', 45.00, 29600.00, 1332000.00, 4, '2026-04-25', '2028-04-25', 24),
('2026-05-20 12:00:00', 55.00, 29583.33, 1627083.15, 5, '2026-05-20', '2028-05-20', 24),
('2026-06-15 13:00:00', 42.00, 29666.67, 1246000.14, 6, '2026-06-15', '2028-06-15', 24),
('2026-07-05 14:00:00', 48.00, 29700.00, 1425600.00, 7, '2026-07-05', '2028-07-05', 24),
('2026-07-11 15:00:00', 38.00, 29800.00, 1132400.00, 8, '2026-07-11', '2028-07-11', 24),
('2026-07-12 16:00:00', 35.00, 29833.33, 1044166.55, 9, '2026-07-12', '2028-07-12', 24),
('2026-07-13 08:00:00', 30.00, 29850.00, 895500.00, 10, '2026-07-13', '2028-07-13', 24);

-- ============================================================================
-- 12. TRANSFORMATIONS DETAIL
-- ============================================================================
INSERT INTO transformations_detail (transformation_id, type_bocal_id, quantite_produite) VALUES
(1, 1, 200), (1, 2, 120), (1, 3, 60),
(2, 1, 160), (2, 2, 96), (2, 3, 48),
(3, 1, 240), (3, 2, 144), (3, 3, 72),
(4, 1, 180), (4, 2, 108), (4, 3, 54),
(5, 1, 220), (5, 2, 132), (5, 3, 66),
(6, 1, 168), (6, 2, 100), (6, 3, 50),
(7, 1, 192), (7, 2, 115), (7, 3, 57),
(8, 1, 152), (8, 2, 91), (8, 3, 45),
(9, 1, 140), (9, 2, 84), (9, 3, 42),
(10, 1, 120), (10, 2, 72), (10, 3, 36);

-- ============================================================================
-- 13. STOCK PRODUIT FINI (mise à jour)
-- ============================================================================
UPDATE stock_produit_fini SET quantite_disponible = 850 WHERE type_bocal_id = 1;
UPDATE stock_produit_fini SET quantite_disponible = 620 WHERE type_bocal_id = 2;
UPDATE stock_produit_fini SET quantite_disponible = 340 WHERE type_bocal_id = 3;

-- ============================================================================
-- 14. SUPERMARCHÉS (données supplémentaires)
-- ============================================================================
INSERT INTO supermarches (nom, contact, telephone, email, localisation, actif) VALUES
('Mada Market Antanimena', 'Rakoto Jean', '+261 20 22 333 44', 'market@madamarket.mg', 'Antanimena, Tana', TRUE),
('Super U Ambohimanarina', 'Rakotondrabe Pierre', '+261 20 22 777 88', 'u@superu.mg', 'Ambohimanarina, Tana', TRUE),
('Carrefour Tana', 'Rasendrasoa Fleur', '+261 20 22 666 77', 'carrefour@mg.mg', 'Tana City', TRUE),
('Acima Alarobia', 'Rajaonarivelo David', '+261 20 22 555 66', 'acima@mg.mg', 'Alarobia, Tana', TRUE);

-- ============================================================================
-- 15. SORTIES
-- ============================================================================
INSERT INTO sorties (date_sortie, supermarche_id, type_bocal_id, quantite, prix_vente_unitaire, valeur_totale, cump_applique, marge_unitaire, marge_totale, motif, commentaire) VALUES
('2026-01-25 10:00:00', 1, 1, 150, 5000.00, 750000.00, 2800.00, 2200.00, 330000.00, 'Vente', 'Commande régulière'),
('2026-02-10 11:00:00', 2, 2, 100, 12000.00, 1200000.00, 7361.11, 4638.89, 463889.00, 'Vente', 'Commande mensuelle'),
('2026-03-15 12:00:00', 3, 3, 80, 23000.00, 1840000.00, 14666.67, 8333.33, 666666.40, 'Vente', 'Promotion'),
('2026-04-20 13:00:00', 4, 1, 200, 5000.00, 1000000.00, 2960.00, 2040.00, 408000.00, 'Vente', 'Stock'),
('2026-05-25 14:00:00', 5, 2, 120, 12000.00, 1440000.00, 7416.67, 4583.33, 550000.00, 'Vente', 'Nouvelle commande'),
('2026-06-30 15:00:00', 6, 3, 90, 23000.00, 2070000.00, 14833.33, 8166.67, 735000.00, 'Vente', 'Fin de mois'),
('2026-07-05 16:00:00', 7, 1, 180, 5000.00, 900000.00, 2970.00, 2030.00, 365400.00, 'Vente', 'Commande urgente'),
('2026-07-10 17:00:00', 8, 2, 110, 12000.00, 1320000.00, 7420.00, 4580.00, 503800.00, 'Vente', 'Réapprovisionnement'),
('2026-07-12 18:00:00', 1, 3, 70, 23000.00, 1610000.00, 14916.67, 8083.33, 565833.10, 'Vente', 'Commande spéciale'),
('2026-07-13 08:00:00', 2, 1, 160, 5000.00, 800000.00, 2985.00, 2015.00, 322400.00, 'Vente', 'Livraison matin');

-- ============================================================================
-- 16. CLIENTS (données supplémentaires)
-- ============================================================================
INSERT INTO clients (nom, type_client, telephone, email, adresse) VALUES
('Hôtel Colbert', 'hotel', '+261 20 22 222 33', 'colbert@hotel.mg', 'Antananarivo'),
('Restaurant La Varangue', 'restaurant', '+261 20 22 111 00', 'varangue@resto.mg', 'Antananarivo'),
('Épicerie du Centre', 'particulier', '+261 34 00 123 45', 'epicerie@centre.mg', 'Antsirabe'),
('Boulangerie Pain Doré', 'particulier', '+261 34 00 234 56', 'pain@boulangerie.mg', 'Fianarantsoa'),
('Café de la Gare', 'particulier', '+261 34 00 345 67', 'cafe@gare.mg', 'Toamasina');

-- ============================================================================
-- 17. VENTES
-- ============================================================================
INSERT INTO ventes (client_id, date_vente, montant_total, mode_paiement, statut) VALUES
(1, '2026-01-25 10:00:00', 1500000.00, 'Virement', 'PAYE'),
(2, '2026-02-10 11:00:00', 5000000.00, 'Virement', 'PAYE'),
(3, '2026-03-15 12:00:00', 6000000.00, 'Virement', 'PAYE'),
(4, '2026-04-20 13:00:00', 2750000.00, 'Cash', 'PAYE'),
(5, '2026-05-25 14:00:00', 3500000.00, 'Virement', 'PAYE'),
(6, '2026-06-30 15:00:00', 4500000.00, 'Virement', 'PAYE'),
(7, '2026-07-05 16:00:00', 1800000.00, 'MVOLA', 'PAYE'),
(8, '2026-07-10 17:00:00', 3200000.00, 'Orange Money', 'PAYE'),
(9, '2026-07-12 18:00:00', 4200000.00, 'Virement', 'PAYE'),
(10, '2026-07-13 08:00:00', 2500000.00, 'Cash', 'EN_COURS');

-- ============================================================================
-- 18. VENTE DETAILS
-- ============================================================================
INSERT INTO vente_details (vente_id, type_bocal_id, quantite, prix_unitaire, total_ligne) VALUES
(1, 1, 150, 5000.00, 750000.00), (1, 2, 62, 12000.00, 750000.00),
(2, 2, 200, 12000.00, 2400000.00), (2, 3, 109, 24000.00, 2616000.00),
(3, 3, 150, 23000.00, 3450000.00), (3, 2, 125, 12000.00, 1500000.00), (3, 1, 210, 5000.00, 1050000.00),
(4, 1, 300, 5000.00, 1500000.00), (4, 2, 104, 12000.00, 1248000.00),
(5, 2, 150, 12000.00, 1800000.00), (5, 3, 74, 23000.00, 1702000.00),
(6, 3, 120, 23000.00, 2760000.00), (6, 2, 75, 12000.00, 900000.00), (6, 1, 168, 5000.00, 840000.00),
(7, 1, 180, 5000.00, 900000.00), (7, 2, 75, 12000.00, 900000.00),
(8, 2, 133, 12000.00, 1596000.00), (8, 3, 70, 23000.00, 1610000.00),
(9, 3, 100, 23000.00, 2300000.00), (9, 2, 158, 12000.00, 1896000.00),
(10, 1, 200, 5000.00, 1000000.00), (10, 2, 125, 12000.00, 1500000.00);

-- ============================================================================
-- 19. LIVREURS
-- ============================================================================
INSERT INTO livreurs (nom, telephone, vehicule, disponible) VALUES
('Rabe Marc', '+261 34 00 456 78', 'Kangoo KA-456', TRUE),
('Andriamanitra Luc', '+261 34 00 567 89', 'Renault Express LU-789', TRUE),
('Rakotomavo Henri', '+261 34 00 012 34', 'Peugeot Partner HE-123', TRUE),
('Rasolofomanana Jeanne', '+261 34 00 123 45', 'Citroën Berlingo JF-456', TRUE),
('Rakotoarisoa Christian', '+261 34 00 234 56', 'Fiat Fiorino CR-789', TRUE);

-- ============================================================================
-- 20. LIVRAISONS
-- ============================================================================
INSERT INTO livraisons (vente_id, livreur_id, date_prevue, date_effective, adresse_livraison, statut) VALUES
(1, 1, '2026-01-26 08:00:00', '2026-01-26 10:30:00', 'Ankorondrano, Tana', 'EFFECTUEE'),
(2, 2, '2026-02-11 09:00:00', '2026-02-11 11:00:00', 'Ivandry, Tana', 'EFFECTUEE'),
(3, 3, '2026-03-16 10:00:00', '2026-03-16 13:00:00', 'Centre-ville Antsirabe', 'EFFECTUEE'),
(4, 1, '2026-04-21 08:00:00', '2026-04-21 11:30:00', 'Behoririka, Tana', 'EFFECTUEE'),
(5, 2, '2026-05-26 09:00:00', '2026-05-26 12:00:00', 'Antanimena, Tana', 'EFFECTUEE'),
(6, 3, '2026-07-01 08:00:00', NULL, 'Ambohimanarina, Tana', 'EN_COURS'),
(7, 1, '2026-07-06 09:00:00', '2026-07-06 14:00:00', 'Tana City', 'EFFECTUEE'),
(8, 2, '2026-07-11 10:00:00', NULL, 'Alarobia, Tana', 'EN_ATTENTE'),
(9, 3, '2026-07-13 08:00:00', NULL, 'Ankorondrano, Tana', 'EN_COURS'),
(10, 1, '2026-07-14 09:00:00', NULL, 'Ivandry, Tana', 'EN_ATTENTE');

-- ============================================================================
-- 21. DISPONIBILITÉS LIVREURS
-- ============================================================================
INSERT INTO disponibilites_livreurs (livreur_id, date_debut, date_fin) VALUES
(1, '2026-07-13 08:00:00', '2026-07-13 18:00:00'),
(1, '2026-07-14 08:00:00', '2026-07-14 18:00:00'),
(1, '2026-07-15 08:00:00', '2026-07-15 18:00:00'),
(2, '2026-07-13 08:00:00', '2026-07-13 18:00:00'),
(2, '2026-07-14 08:00:00', '2026-07-14 18:00:00'),
(3, '2026-07-13 08:00:00', '2026-07-13 18:00:00'),
(3, '2026-07-14 08:00:00', '2026-07-14 18:00:00'),
(3, '2026-07-15 08:00:00', '2026-07-15 18:00:00'),
(4, '2026-07-13 08:00:00', '2026-07-13 18:00:00'),
(5, '2026-07-13 08:00:00', '2026-07-13 18:00:00');

-- ============================================================================
-- 22. COMPTES TRÉSORERIE (mise à jour)
-- ============================================================================
UPDATE comptes_tresorerie SET solde = 2500000.00 WHERE nom = 'CAISSE';
UPDATE comptes_tresorerie SET solde = 15000000.00 WHERE nom = 'BNI';
UPDATE comptes_tresorerie SET solde = 3200000.00 WHERE nom = 'MVOLA';
UPDATE comptes_tresorerie SET solde = 2800000.00 WHERE nom = 'ORANGE MONEY';
INSERT INTO comptes_tresorerie (nom, solde) VALUES ('BOA', 8500000.00);

-- ============================================================================
-- 23. MOUVEMENTS FINANCIERS
-- ============================================================================
INSERT INTO mouvements_financiers (compte_id, type, categorie, montant, description, date_transaction, created_at, updated_at) VALUES
-- Recettes
(2, 'recette', 'Vente', 1500000.00, 'Vente Jumbo Score', '2026-01-25 10:00:00', '2026-01-25 10:00:00', '2026-01-25 10:00:00'),
(2, 'recette', 'Vente', 5000000.00, 'Vente Leader Price', '2026-02-10 11:00:00', '2026-02-10 11:00:00', '2026-02-10 11:00:00'),
(2, 'recette', 'Vente', 6000000.00, 'Vente Shoprite', '2026-03-15 12:00:00', '2026-03-15 12:00:00', '2026-03-15 12:00:00'),
(1, 'recette', 'Vente', 2750000.00, 'Vente Score', '2026-04-20 13:00:00', '2026-04-20 13:00:00', '2026-04-20 13:00:00'),
(2, 'recette', 'Vente', 3500000.00, 'Vente Mada Market', '2026-05-25 14:00:00', '2026-05-25 14:00:00', '2026-05-25 14:00:00'),
(2, 'recette', 'Vente', 4500000.00, 'Vente Super U', '2026-06-30 15:00:00', '2026-06-30 15:00:00', '2026-06-30 15:00:00'),
(3, 'recette', 'Vente', 1800000.00, 'Vente Carrefour', '2026-07-05 16:00:00', '2026-07-05 16:00:00', '2026-07-05 16:00:00'),
(4, 'recette', 'Vente', 3200000.00, 'Vente Acima', '2026-07-10 17:00:00', '2026-07-10 17:00:00', '2026-07-10 17:00:00'),
(2, 'recette', 'Vente', 4200000.00, 'Vente Jumbo Score', '2026-07-12 18:00:00', '2026-07-12 18:00:00', '2026-07-12 18:00:00'),
-- Dépenses
(2, 'depense', 'Achat miel', 2800000.00, 'Achat miel brut LOT-001', '2026-01-15 08:00:00', '2026-01-15 08:00:00', '2026-01-15 08:00:00'),
(2, 'depense', 'Achat miel', 2320000.00, 'Achat miel brut LOT-002', '2026-02-10 09:00:00', '2026-02-10 09:00:00', '2026-02-10 09:00:00'),
(2, 'depense', 'Achat miel', 3720000.00, 'Achat miel brut LOT-003', '2026-03-05 10:00:00', '2026-03-05 10:00:00', '2026-03-05 10:00:00'),
(2, 'depense', 'Achat miel', 2745000.00, 'Achat miel brut LOT-004', '2026-04-20 11:00:00', '2026-04-20 11:00:00', '2026-04-20 11:00:00'),
(2, 'depense', 'Achat miel', 3245000.00, 'Achat miel brut LOT-005', '2026-05-15 12:00:00', '2026-05-15 12:00:00', '2026-05-15 12:00:00'),
(2, 'depense', 'Salaire', 1550000.00, 'Salaire janvier', '2026-01-30 15:00:00', '2026-01-30 15:00:00', '2026-01-30 15:00:00'),
(2, 'depense', 'Salaire', 1590000.00, 'Salaire février', '2026-02-28 15:00:00', '2026-02-28 15:00:00', '2026-02-28 15:00:00'),
(2, 'depense', 'Salaire', 1565000.00, 'Salaire mars', '2026-03-31 15:00:00', '2026-03-31 15:00:00', '2026-03-31 15:00:00'),
(2, 'depense', 'Carburant', 450000.00, 'Carburant livraisons', '2026-06-30 16:00:00', '2026-06-30 16:00:00', '2026-06-30 16:00:00'),
(1, 'depense', 'Frais divers', 150000.00, 'Frais bureau', '2026-07-10 17:00:00', '2026-07-10 17:00:00', '2026-07-10 17:00:00');

-- ============================================================================
-- 24. NOTIFICATIONS
-- ============================================================================
INSERT INTO notifications (utilisateur_id, titre, message, type, lien, lu, date_creation) VALUES
-- Admin notifications
(1, 'Stock matière première faible', 'Le stock de miel brut est inférieur au seuil d''alerte (50L).', 'WARNING', '/stock/matiere-premiere', FALSE, '2026-07-13 08:30:00'),
(1, 'Nouvelle vente enregistrée', 'Une nouvelle commande de 5 000 000 Ar a été enregistrée pour Leader Price Ivandry.', 'SUCCESS', '/ventes', FALSE, '2026-07-13 09:15:00'),
(1, 'Paiement salaire effectué', 'Les salaires du mois de mars ont été payés avec succès.', 'INFO', '/rh/salaires', TRUE, '2026-07-12 16:00:00'),
(1, 'Contrat bientôt expiré', 'Le contrat de distribution avec Jumbo Score expire dans 30 jours.', 'ERROR', '/contrats', FALSE, '2026-07-13 10:00:00'),
(1, 'Nouvelle production terminée', '800 bocaux de miel 25cl ont été ajoutés au stock.', 'SUCCESS', '/production', TRUE, '2026-07-12 14:30:00'),
(1, 'Alerte stock produit fini', 'Le stock des bocaux 10cl approche du seuil minimum.', 'WARNING', '/stock/produits', FALSE, '2026-07-13 07:45:00'),
(1, 'Livraison en retard', 'La livraison #6 est en retard de 2 jours.', 'ERROR', '/livraisons', FALSE, '2026-07-13 11:00:00'),
(1, 'Nouveau mouvement financier', 'Recette de 6 000 000 Ar enregistrée.', 'INFO', '/finance/mouvements', FALSE, '2026-07-13 12:00:00'),
-- Magasinier notifications
(3, 'Alerte stock produit fini', 'Le stock des bocaux 10cl approche du seuil minimum (100 unités).', 'WARNING', '/stock/produits', FALSE, '2026-07-13 07:45:00'),
(3, 'Transformation terminée', 'La transformation du 13/07/2026 est terminée.', 'SUCCESS', '/production', FALSE, '2026-07-13 09:00:00'),
(3, 'Entrée matière première', 'Nouvelle entrée de 50L de miel brut.', 'INFO', '/stock/matiere-premiere', FALSE, '2026-07-13 08:00:00'),
-- Comptable notifications
(2, 'Nouveau mouvement financier', 'Une recette de 6 000 000 Ar vient d''être enregistrée.', 'INFO', '/finance/mouvements', FALSE, '2026-07-13 11:00:00'),
(2, 'Paiement salaire à programmer', 'Les salaires de juillet doivent être payés.', 'WARNING', '/rh/salaires', FALSE, '2026-07-13 10:30:00'),
(2, 'Facture à émettre', 'Facture pour vente #10 à émettre.', 'INFO', '/factures', FALSE, '2026-07-13 09:30:00'),
-- Livreur notifications
(4, 'Nouvelle livraison assignée', 'Livraison #9 vous a été assignée.', 'INFO', '/livraisons', FALSE, '2026-07-13 08:00:00'),
(4, 'Livraison terminée', 'Livraison #7 marquée comme effectuée.', 'SUCCESS', '/livraisons', TRUE, '2026-07-06 14:00:00'),
(5, 'Nouvelle livraison assignée', 'Livraison #8 vous a été assignée.', 'INFO', '/livraisons', FALSE, '2026-07-11 10:00:00'),
-- Responsable notifications
(5, 'Rapport mensuel disponible', 'Le rapport financier de juin est disponible.', 'INFO', '/finance/rapport', FALSE, '2026-07-01 09:00:00'),
(5, 'Réunion équipe', 'Réunion d''équipe prévue le 15/07/2026.', 'INFO', '/planning', TRUE, '2026-07-10 16:00:00'),
(5, 'Objectif atteint', 'Objectif de vente du mois de juin atteint à 105%.', 'SUCCESS', '/statistiques', TRUE, '2026-07-01 10:00:00');
