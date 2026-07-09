<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteDetailModel extends Model
{
    protected $table         = 'vente_details';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'vente_id', 'type_bocal_id', 'quantite', 'prix_unitaire', 'total_ligne'
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getLignesFacture(int $venteId)
    {
        return $this->select('vente_details.*, types_bocaux.nom as bocal_nom')
                    ->join('types_bocaux', 'types_bocaux.id = vente_details.type_bocal_id')
                    ->where('vente_id', $venteId)
                    ->findAll();
    }

    public function supprimerLignesDeVente(int $venteId)
    {
        return $this->where('vente_id', $venteId)->delete();
    }
}
