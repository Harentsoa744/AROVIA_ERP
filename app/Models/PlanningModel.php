<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanningModel extends Model
{
    protected $table = 'planning';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'employe_id',
        'date_debut',
        'date_fin',
        'type_evenement',
        'description'
    ];

    public function getByEmploye($employe_id)
    {
        return $this->where('employe_id', $employe_id)->findAll();
    }
}