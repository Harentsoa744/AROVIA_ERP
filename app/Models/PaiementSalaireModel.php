<?php

namespace App\Models;

use CodeIgniter\Model;

class PaiementSalaireModel extends Model
{
    protected $table = 'paiements_salaires';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'employe_id',
        'mois',
        'annee',
        'salaire_base',
        'primes',
        'deductions',
        'montant_paye',
        'date_paiement',
        'commentaire'
    ];

    // paiements d’un employé
    public function getByEmploye($employe_id)
    {
        return $this->where('employe_id', $employe_id)->findAll();
    }
}