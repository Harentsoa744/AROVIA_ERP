-- Création de la table ventes liée aux sorties pour résoudre les problèmes de facture et distribution
-- Cette table permet de lier les sorties de stock aux ventes facturées

CREATE TABLE ventes (
    id            SERIAL PRIMARY KEY,
    sortie_id     INTEGER REFERENCES sorties(id) ON DELETE CASCADE,
    client_id     INTEGER REFERENCES clients(id),
    date_vente    TIMESTAMP DEFAULT NOW(),
    montant_total NUMERIC(12,2),
    mode_paiement VARCHAR(50),
    statut        VARCHAR(50) DEFAULT 'EN_COURS', -- 'PAYE', 'EN_COURS'
    commentaire   TEXT,
    created_at    TIMESTAMP DEFAULT NOW(),
    updated_at    TIMESTAMP DEFAULT NOW()
);

-- Table pour les détails de vente (pour les factures avec plusieurs lignes)
CREATE TABLE vente_details (
    id            SERIAL PRIMARY KEY,
    vente_id      INTEGER REFERENCES ventes(id) ON DELETE CASCADE,
    type_bocal_id INTEGER REFERENCES types_bocaux(id),
    quantite      INTEGER NOT NULL,
    prix_unitaire NUMERIC(12,2) NOT NULL,
    total_ligne   NUMERIC(12,2) NOT NULL
);

-- Index pour optimiser les requêtes
CREATE INDEX idx_ventes_sortie_id ON ventes(sortie_id);
CREATE INDEX idx_ventes_client_id ON ventes(client_id);
CREATE INDEX idx_ventes_date_vente ON ventes(date_vente);
CREATE INDEX idx_vente_details_vente_id ON vente_details(vente_id);

-- Trigger pour mettre à jour updated_at automatiquement
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_ventes_updated_at BEFORE UPDATE ON ventes
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Vue pour simplifier les requêtes de ventes avec informations de sortie
CREATE OR REPLACE VIEW v_ventes_completes AS
SELECT 
    v.id,
    v.sortie_id,
    v.client_id,
    v.date_vente,
    v.montant_total,
    v.mode_paiement,
    v.statut,
    v.commentaire,
    s.date_sortie,
    s.supermarche_id,
    s.type_bocal_id,
    s.quantite as quantite_sortie,
    s.prix_vente_unitaire,
    s.valeur_totale as valeur_sortie,
    s.marge_totale,
    sb.nom as bocal_nom,
    sb.volume_litres as bocal_volume,
    sup.nom as supermarche_nom,
    c.nom as client_nom,
    c.type_client as client_type
FROM ventes v
LEFT JOIN sorties s ON v.sortie_id = s.id
LEFT JOIN types_bocaux sb ON s.type_bocal_id = sb.id
LEFT JOIN supermarches sup ON s.supermarche_id = sup.id
LEFT JOIN clients c ON v.client_id = c.id;
