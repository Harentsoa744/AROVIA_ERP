<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteModel extends Model
{
    protected $table         = 'ventes';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'client_id', 'date_vente', 'montant_total', 'mode_paiement', 'statut'
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getListeVentes()
    {
        return $this->select('ventes.*, clients.nom as client_nom')
                    ->join('clients', 'clients.id = ventes.client_id')
                    ->orderBy('date_vente', 'DESC')
                    ->findAll();
    }

    public function getVenteAvecClient(int $id)
    {
        return $this->select('ventes.*, clients.nom as client_nom, clients.adresse as client_adresse, clients.telephone as client_telephone, clients.email as client_email')
                    ->join('clients', 'clients.id = ventes.client_id')
                    ->where('ventes.id', $id)
                    ->first();
    }
}
