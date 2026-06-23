<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeBocalModel extends Model
{
    protected $table         = 'types_bocaux';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom', 'volume_litres', 'cible', 'prix_vente'];

    protected $returnType    = 'array';
    protected $useTimestamps = false;
}