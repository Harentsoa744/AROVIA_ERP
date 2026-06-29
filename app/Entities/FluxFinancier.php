<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class FluxFinancier extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'date_transaction', 'date_sortie', 'date_entree'];
}