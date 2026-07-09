-- ============================================================================
-- NOTIFICATIONS TEST
-- ============================================================================


INSERT INTO notifications
(
 utilisateur_id,
 titre,
 message,
 type,
 lien,
 lu,
 date_creation
)
VALUES



-- ADMIN : notification non lue
(
1,
'Stock matière première faible',
'Le stock de miel brut est inférieur au seuil d''alerte.',
'WARNING',
'/stock/matiere-premiere',
FALSE,
'2026-07-09 08:30:00'
),



-- ADMIN : notification non lue
(
1,
'Nouvelle vente enregistrée',
'Une nouvelle commande de 5 000 000 Ar a été enregistrée pour Leader Price Ivandry.',
'SUCCESS',
'/ventes',
FALSE,
'2026-07-09 09:15:00'
),



-- ADMIN : notification lue
(
1,
'Paiement salaire effectué',
'Les salaires du mois de février ont été payés avec succès.',
'INFO',
'/rh/salaires',
TRUE,
'2026-07-08 16:00:00'
),



-- ADMIN : notification non lue
(
1,
'Contrat bientôt expiré',
'Le contrat de distribution avec Jumbo Score expire dans 30 jours.',
'ERROR',
'/contrats',
FALSE,
'2026-07-09 10:00:00'
),



-- ADMIN : notification lue
(
1,
'Nouvelle production terminée',
'800 bocaux de miel 25cl ont été ajoutés au stock.',
'SUCCESS',
'/production',
TRUE,
'2026-07-07 14:30:00'
),



-- MAGASINIER (id 3)
(
3,
'Alerte stock produit fini',
'Le stock des bocaux 10cl approche du seuil minimum.',
'WARNING',
'/stock/produits',
FALSE,
'2026-07-09 07:45:00'
),



-- COMPTABLE (id 2)
(
2,
'Nouveau mouvement financier',
'Une recette de 6 000 000 Ar vient d''être enregistrée.',
'INFO',
'/finance/mouvements',
FALSE,
'2026-07-09 11:00:00'
);