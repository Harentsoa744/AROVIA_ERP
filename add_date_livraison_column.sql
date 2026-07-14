-- Script pour ajouter la colonne date_livraison à la table sorties
-- À exécuter sur la base de données existante

ALTER TABLE sorties ADD COLUMN date_livraison TIMESTAMP DEFAULT NULL;

-- Ajouter un commentaire
COMMENT ON COLUMN sorties.date_livraison IS 'Date et heure de livraison prévue';
