<?php

namespace App\Models;

use CodeIgniter\Model;

class EntreeMatierePremiereModel extends Model
{
    protected $table         = 'entrees_matiere_premiere';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'fournisseur_id',
        'numero_lot',
        'date_entree',
        'quantite_litres',
        'prix_unitaire',
        'valeur_totale',
        'cump_apres_entree',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    
    public function getEntreesAvecFournisseur(array $filtres = [])
{
    $builder = $this->select('entrees_matiere_premiere.*, fournisseurs.nom as fournisseur_nom')
                ->join('fournisseurs', 'fournisseurs.id = entrees_matiere_premiere.fournisseur_id');

    if (! empty($filtres['fournisseur_id'])) {
        $builder->where('entrees_matiere_premiere.fournisseur_id', $filtres['fournisseur_id']);
    }

    if (! empty($filtres['date_debut'])) {
        $builder->where('entrees_matiere_premiere.date_entree >=', $filtres['date_debut'] . ' 00:00:00');
    }

    if (! empty($filtres['date_fin'])) {
        $builder->where('entrees_matiere_premiere.date_entree <=', $filtres['date_fin'] . ' 23:59:59');
    }

    return $builder->orderBy('date_entree', 'DESC')->findAll();
}

// Pour la courbe : total des litres entrés, groupé par jour
public function getStatistiquesParDate(): array
{
    return $this->select('DATE(date_entree) as jour, SUM(quantite_litres) as total_litres')
                ->groupBy('DATE(date_entree)')
                ->orderBy('jour', 'ASC')
                ->findAll();
}

// Pour le camembert : total des litres entrés, groupé par fournisseur
public function getStatistiquesParFournisseur(): array
{
    return $this->select('fournisseurs.nom as fournisseur_nom, SUM(entrees_matiere_premiere.quantite_litres) as total_litres')
                ->join('fournisseurs', 'fournisseurs.id = entrees_matiere_premiere.fournisseur_id')
                ->groupBy('fournisseurs.nom')
                ->orderBy('total_litres', 'DESC')
                ->findAll();
}




}