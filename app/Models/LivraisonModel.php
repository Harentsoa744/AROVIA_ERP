<?php

namespace App\Models;

use CodeIgniter\Model;

class LivraisonModel extends Model
{
    protected $table = 'livraisons';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sortie_id',
        'livreur_id',
        'date_prevue',
        'date_effective',
        'adresse_livraison',
        'statut'
    ];

    // Récupérer les livraisons avec les informations du livreur jointes
    public function getLivraisonsWithLivreur($statuts = [])
    {
        $builder = $this->select('livraisons.*, livreurs.nom as livreur_nom, livreurs.telephone as livreur_telephone')
                        ->join('livreurs', 'livreurs.id = livraisons.livreur_id', 'left');

        if (!empty($statuts)) {
            $builder->whereIn('livraisons.statut', $statuts);
        }

        return $builder->orderBy('livraisons.date_prevue', 'DESC')->findAll();
    }

    // Récupérer les statistiques rapides de livraison
    public function getStats()
    {
        return [
            'en_cours' => $this->where('statut', 'EN_COURS')->countAllResults(),
            'effectuees' => $this->where('statut', 'EFFECTUEE')->countAllResults(),
            'annulees' => $this->where('statut', 'ANNULEE')->countAllResults(),
            'en_attente' => $this->where('statut', 'EN_ATTENTE')->countAllResults(),
        ];
    }
}