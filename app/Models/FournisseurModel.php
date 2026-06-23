<?php

namespace App\Models;

use CodeIgniter\Model;

class FournisseurModel extends Model
{
    protected $table            = 'fournisseurs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom', 'contact', 'localisation'];

    protected $returnType       = 'array';
    protected $useTimestamps    = false;

    // Règles de validation appliquées automatiquement à insert()/update()
    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[150]',
    ];
    protected $validationMessages = [
        'nom' => [
            'required'   => 'Le nom du fournisseur est obligatoire.',
            'min_length' => 'Le nom doit contenir au moins 2 caractères.',
        ],
    ];
}