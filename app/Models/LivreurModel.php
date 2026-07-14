<?php

namespace App\Models;

use CodeIgniter\Model;

class LivreurModel extends Model
{
    protected $table = 'livreurs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'telephone',
        'vehicule',
        'disponible'
    ];

    // Récupérer les livreurs actifs/disponibles pour les recommandations
    public function getDisponibles()
    {
        return $this->where('disponible', true)->findAll();
    }

    // Récupérer tous les livreurs (pour le formulaire d'assignation)
    public function getAllLivreurs()
    {
        return $this->findAll();
    }
}