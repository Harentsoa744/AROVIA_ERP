<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteDetailModel extends Model
{
    protected $table            = 'vente_details';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'vente_id',
        'type_bocal_id',
        'quantite',
        'prix_unitaire',
        'total_ligne'
    ];

    /**
     * Récupère les lignes d'une facture/vente
     */
    public function getLignesFacture(int $venteId): array
    {
        return $this->select('vente_details.*, types_bocaux.nom as bocal_nom, types_bocaux.volume_litres')
            ->join('types_bocaux', 'types_bocaux.id = vente_details.type_bocal_id')
            ->where('vente_details.vente_id', $venteId)
            ->findAll();
    }

    /**
     * Supprime toutes les lignes d'une vente
     */
    public function supprimerLignesDeVente(int $venteId): bool
    {
        return $this->where('vente_id', $venteId)->delete();
    }

    /**
     * Crée les lignes de détail pour une vente à partir d'une sortie
     */
    public function creerDepuisSortie(int $venteId, int $sortieId): bool
    {
        $db = \Config\Database::connect();
        
        // Récupérer les informations de la sortie
        $sortie = $db->table('sorties')
            ->where('id', $sortieId)
            ->get()
            ->getRowArray();

        if (!$sortie) {
            return false;
        }

        // Créer une ligne de détail pour la sortie
        $this->insert([
            'vente_id'      => $venteId,
            'type_bocal_id' => $sortie['type_bocal_id'],
            'quantite'      => $sortie['quantite'],
            'prix_unitaire' => $sortie['prix_vente_unitaire'],
            'total_ligne'   => $sortie['valeur_totale']
        ]);

        return true;
    }
}
