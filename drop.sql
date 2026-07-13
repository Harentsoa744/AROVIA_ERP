DROP TABLE IF EXISTS disponibilites_livreurs   CASCADE;
DROP TABLE IF EXISTS livraisons               CASCADE;
DROP TABLE IF EXISTS livreurs                 CASCADE;
DROP TABLE IF EXISTS vente_details            CASCADE;
DROP TABLE IF EXISTS ventes                   CASCADE;
DROP TABLE IF EXISTS clients                  CASCADE;

-- Logistique, Commercialisation & Stock produits finis
DROP TABLE IF EXISTS sorties                  CASCADE;
DROP TABLE IF EXISTS supermarches             CASCADE;
DROP TABLE IF EXISTS stock_produit_fini       CASCADE; -- Placé avant types_bocaux
DROP TABLE IF EXISTS transformations_detail   CASCADE;
DROP TABLE IF EXISTS transformations          CASCADE;
DROP TABLE IF EXISTS types_bocaux             CASCADE;

-- Matières premières
DROP TABLE IF EXISTS entrees_matiere_premiere CASCADE;
DROP TABLE IF EXISTS stock_matiere_premiere   CASCADE;
DROP TABLE IF EXISTS fournisseurs             CASCADE;

-- Comptabilité & RH
DROP TABLE IF EXISTS mouvements_financiers    CASCADE;
DROP TABLE IF EXISTS comptes_tresorerie        CASCADE;
DROP TABLE IF EXISTS paiements_salaires       CASCADE;
DROP TABLE IF EXISTS planning                 CASCADE;
DROP TABLE IF EXISTS contrats                 CASCADE;
DROP TABLE IF EXISTS employes                 CASCADE;
DROP TABLE IF EXISTS entreprise               CASCADE;
DROP TABLE IF EXISTS statut                   CASCADE;

-- Système & Utilisateurs
DROP TABLE IF EXISTS notifications            CASCADE; -- Centralisé ici
DROP TABLE IF EXISTS utilisateurs             CASCADE;
DROP TABLE IF EXISTS roles                    CASCADE;

-- Vues
DROP VIEW  IF EXISTS transactions             CASCADE;