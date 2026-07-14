-- Mise à jour de la table livraisons pour utiliser sortie_id au lieu de vente_id
-- Cela permet de lier directement les livraisons aux sorties de stock

-- Supprimer la contrainte de clé étrangère existante sur vente_id
ALTER TABLE livraisons DROP CONSTRAINT IF EXISTS livraisons_vente_id_fkey;

-- Supprimer la colonne vente_id
ALTER TABLE livraisons DROP COLUMN IF EXISTS vente_id;

-- Ajouter la colonne sortie_id avec clé étrangère
ALTER TABLE livraisons ADD COLUMN sortie_id INTEGER REFERENCES sorties(id) ON DELETE SET NULL;

-- Mettre à jour les données existantes si nécessaire (optionnel)
-- UPDATE livraisons SET sortie_id = ... WHERE vente_id IS NOT NULL;

-- Créer un index pour optimiser les requêtes
CREATE INDEX IF NOT EXISTS idx_livraisons_sortie_id ON livraisons(sortie_id);
