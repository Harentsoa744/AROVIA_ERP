<?php

namespace App\Models;

use CodeIgniter\Model;

class HistoriqueLivraisonModel extends Model
{
    protected $table = 'historique_livraison';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'livraison_id',
        'statut_precedent',
        'statut_nouveau',
        'date_changement',
        'utilisateur_id',
        'commentaire'
    ];

    protected $returnType = 'array';
    protected $useTimestamps = false;

    /**
     * Enregistre un changement de statut de livraison dans l'historique
     */
    public function enregistrerChangement(int $livraisonId, string $statutPrecedent, string $statutNouveau, ?int $utilisateurId = null, ?string $commentaire = null): bool
    {
        return $this->insert([
            'livraison_id' => $livraisonId,
            'statut_precedent' => $statutPrecedent,
            'statut_nouveau' => $statutNouveau,
            'date_changement' => date('Y-m-d H:i:s'),
            'utilisateur_id' => $utilisateurId,
            'commentaire' => $commentaire
        ]);
    }

    /**
     * Récupère l'historique d'une livraison
     */
    public function getHistoriqueLivraison(int $livraisonId): array
    {
        return $this->where('livraison_id', $livraisonId)
                    ->orderBy('date_changement', 'DESC')
                    ->findAll();
    }
}
