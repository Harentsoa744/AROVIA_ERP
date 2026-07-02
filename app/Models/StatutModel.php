<?php

namespace App\Models;

use CodeIgniter\Model;

class StatutModel extends Model
{
    protected $table      = 'statut';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nom'];

    protected $returnType = 'array';
}
