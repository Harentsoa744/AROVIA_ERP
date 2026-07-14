<?php

namespace App\Models;

use CodeIgniter\Model;

class VenteModel extends Model
{
    protected $table            = 'ventes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'sortie_id',
        'client_id',
        'date_vente',
        'montant_total',
        'mode_paiement',
        'statut',
        'commentaire'
    ];

    /**
     * Récupère une vente avec les informations de sortie et client
     */
    public function getVenteAvecClient(int $id): ?array
    {
        return $this->select('ventes.*, sorties.*, supermarches.nom as supermarche_nom, clients.nom as client_nom, clients.type_client')
            ->join('sorties', 'sorties.id = ventes.sortie_id', 'left')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left')
            ->join('clients', 'clients.id = ventes.client_id', 'left')
            ->where('ventes.id', $id)
            ->first();
    }

    /**
     * Liste de toutes les ventes avec informations associées
     */
    public function getListeVentes(): array
    {
        return $this->select('ventes.*, sorties.date_sortie, supermarches.nom as supermarche_nom, clients.nom as client_nom')
            ->join('sorties', 'sorties.id = ventes.sortie_id', 'left')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left')
            ->join('clients', 'clients.id = ventes.client_id', 'left')
            ->orderBy('ventes.date_vente', 'DESC')
            ->findAll();
    }

    /**
     * Crée une vente à partir d'une sortie
     */
    public function creerDepuisSortie(int $sortieId, int $clientId, string $modePaiement): int
    {
        $db = \Config\Database::connect();
        
        // Récupérer les informations de la sortie
        $sortie = $db->table('sorties')
            ->where('id', $sortieId)
            ->get()
            ->getRowArray();

        if (!$sortie) {
            throw new \Exception('Sortie introuvable');
        }

        // Créer la vente
        $venteId = $this->insert([
            'sortie_id'     => $sortieId,
            'client_id'     => $clientId,
            'date_vente'    => date('Y-m-d H:i:s'),
            'montant_total' => $sortie['valeur_totale'],
            'mode_paiement' => $modePaiement,
            'statut'        => 'EN_COURS'
        ]);

        return $venteId;
    }

    /**
     * Récupère les ventes par période pour les statistiques
     */
    public function getVentesParPeriode(string $dateDebut, string $dateFin): array
    {
        return $this->select('ventes.*, sorties.*, supermarches.nom as supermarche_nom')
            ->join('sorties', 'sorties.id = ventes.sortie_id')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id')
            ->where('ventes.date_vente >=', $dateDebut)
            ->where('ventes.date_vente <=', $dateFin)
            ->orderBy('ventes.date_vente', 'DESC')
            ->findAll();
    }
}
