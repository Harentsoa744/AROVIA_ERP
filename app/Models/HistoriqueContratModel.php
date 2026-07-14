<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueContratModel extends Model
{
    protected $table = 'historique_contrat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'contrat_id',
        'statut_precedent',
        'statut_nouveau',
        'date_changement',
        'utilisateur_id',
        'commentaire'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    /**
     * Enregistre un changement de statut de contrat dans l'historique
     */
    public function enregistrerChangement(int $contratId, string $statutPrecedent, string $statutNouveau, ?int $utilisateurId = null, ?string $commentaire = null): bool
    {
        return $this->insert([
            'contrat_id' => $contratId,
            'statut_precedent' => $statutPrecedent,
            'statut_nouveau' => $statutNouveau,
            'date_changement' => date('Y-m-d H:i:s'),
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire
        ]);
    }

    /**
     * Récupère l'historique d'un contrat
     */
    public function getHistoriqueContrat(int $contratId): array
    {
        return $this->where('contrat_id', $contratId)
                    ->orderBy('date_changement', 'DESC')
                    ->findAll();
    }
}
