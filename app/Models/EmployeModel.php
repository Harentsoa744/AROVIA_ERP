<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'matricule',
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
        'poste',
        'salaire_base',
        'date_embauche',
        'date_fin_contrat',
        'statut'
    ];

    public function getActifs()
    {
        return $this->where('statut', 'ACTIF')->findAll();
    }


    public function getWithPaiements($id)
    {
        return $this->select('employes.*')
            ->where('id', $id)
            ->first();
    }
}