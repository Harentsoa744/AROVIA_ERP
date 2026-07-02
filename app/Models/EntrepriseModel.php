<?php

namespace App\Models;

use CodeIgniter\Model;

class EntrepriseModel extends Model
{
    protected $table      = 'entreprise';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nom', 'telephone', 'email'];

    protected $returnType = 'array';

    public function possedeContrats(int $entrepriseId): bool
    {
        $nombre = $this->db->table('contrat')
            ->where('entreprise_id', $entrepriseId)
            ->countAllResults();

        return $nombre > 0;
    }
}
