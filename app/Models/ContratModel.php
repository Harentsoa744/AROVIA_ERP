<?php

namespace App\Models;

use CodeIgniter\Model;


class ContratModel extends Model
{
    protected $table      = 'contrat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'sujet',
        'entreprise_id',
        'description',
        'date_signature',
        'date_expiration',
        'statut_id',
    ];

    protected $returnType = 'array';

   
    public function getListe(string $recherche = '', ?int $statutId = null): array
    {
        $builder = $this->db->table('contrat c');

        $builder->select('c.id, c.sujet, c.date_creation, e.nom AS entreprise_nom, s.id AS statut_id, s.nom AS statut_nom');
        $builder->join('entreprise e', 'e.id = c.entreprise_id');
        $builder->join('statut s', 's.id = c.statut_id');

        if ($recherche !== '') {
            $builder->groupStart();
                $builder->like('c.sujet', $recherche);
                $builder->orLike('e.nom', $recherche);

                if (is_numeric($recherche)) {
                    $builder->orWhere('c.id', (int) $recherche);
                }
            $builder->groupEnd();
        }

        if ($statutId !== null) {
            $builder->where('s.id', $statutId);
        }

        $builder->orderBy('c.id', 'DESC');

        return $builder->get()->getResultArray();
    }

    
    public function getDetail(int $id): ?array
    {
        $builder = $this->db->table('contrat c');

        $builder->select('c.*, e.nom AS entreprise_nom, e.telephone AS entreprise_telephone, e.email AS entreprise_email, s.nom AS statut_nom');
        $builder->join('entreprise e', 'e.id = c.entreprise_id');
        $builder->join('statut s', 's.id = c.statut_id');
        $builder->where('c.id', $id);

        $resultat = $builder->get()->getRowArray();

        return $resultat ?: null;
    }
}
