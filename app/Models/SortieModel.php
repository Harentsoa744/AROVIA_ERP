<?php

namespace App\Models;

use CodeIgniter\Model;

class SortieModel extends Model
{
    protected $table         = 'sorties';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'date_sortie', 'type_bocal_id', 'quantite',
        'supermarche_id', 'prix_vente_unitaire', 'valeur_totale',
        'cump_applique'
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getSortiesAvecBocal(array $filtres = [])
    {
        $builder = $this->select('sorties.*, types_bocaux.nom as bocal_nom, types_bocaux.volume_litres as bocal_volume, supermarches.nom as supermarche_nom')
                    ->join('types_bocaux', 'types_bocaux.id = sorties.type_bocal_id')
                    ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left');

        if (! empty($filtres['type_bocal_id'])) {
            $builder->where('sorties.type_bocal_id', $filtres['type_bocal_id']);
        }

        if (! empty($filtres['supermarche_id'])) {
            $builder->where('sorties.supermarche_id', $filtres['supermarche_id']);
        }

        if (! empty($filtres['date_debut'])) {
            $builder->where('sorties.date_sortie >=', $filtres['date_debut'] . ' 00:00:00');
        }

        if (! empty($filtres['date_fin'])) {
            $builder->where('sorties.date_sortie <=', $filtres['date_fin'] . ' 23:59:59');
        }

        return $builder->orderBy('date_sortie', 'DESC')->findAll();
    }

    /**
     * Enregistre une vente/sortie : vérifie le stock disponible,
     * décrémente le stock produit fini, enregistre la sortie avec CUMP appliqué.
     * Protégé par transaction + verrouillage de ligne.
     */
    public function enregistrerSortie(int $typeBocalId, int $quantite, int $supermarcheId, float $prixUnitaire): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Verrouille la ligne de stock produit fini concernée
        $stockPF = $db->query(
            'SELECT * FROM stock_produit_fini WHERE type_bocal_id = ? FOR UPDATE',
            [$typeBocalId]
        )->getRowArray();

        if (! $stockPF || $quantite > $stockPF['quantite_disponible']) {
            $db->transRollback();
            return ['succes' => false, 'message' => 'Stock insuffisant pour ce type de bocal.'];
        }

        // Récupère le CUMP actuel depuis stock_matiere_premiere
        $stockMP = $db->query('SELECT cump_actuel FROM stock_matiere_premiere LIMIT 1')->getRowArray();
        $cumpApplique = $stockMP['cump_actuel'] ?? 0;

        $valeurTotale = $quantite * $prixUnitaire;

        // Décrémente le stock produit fini
        $db->table('stock_produit_fini')
           ->where('type_bocal_id', $typeBocalId)
           ->update(['quantite_disponible' => $stockPF['quantite_disponible'] - $quantite]);

        // Enregistre la sortie
        $db->table('sorties')->insert([
            'date_sortie'          => date('Y-m-d H:i:s'),
            'type_bocal_id'        => $typeBocalId,
            'quantite'             => $quantite,
            'supermarche_id'       => $supermarcheId,
            'prix_vente_unitaire'  => $prixUnitaire,
            'valeur_totale'        => $valeurTotale,
            'cump_applique'        => $cumpApplique,
        ]);

        $db->transComplete();

        return ['succes' => $db->transStatus(), 'valeur_totale' => $valeurTotale];
    }

    // Pour la courbe : total des bocaux vendus, groupé par jour
    public function getStatistiquesParDate(): array
    {
        return $this->select('DATE(date_sortie) as jour, SUM(quantite) as total_quantite')
                    ->groupBy('DATE(date_sortie)')
                    ->orderBy('jour', 'ASC')
                    ->findAll();
    }

    // Pour le camembert : total des bocaux vendus, groupé par supermarché
    public function getStatistiquesParSupermarche(): array
    {
        return $this->select('supermarches.nom as supermarche_nom, SUM(sorties.quantite) as total_quantite')
                    ->join('supermarches', 'supermarches.id = sorties.supermarche_id')
                    ->groupBy('supermarches.id, supermarches.nom')
                    ->findAll();
    }

    // Récupérer les marges consolidées par supermarché
    public function getMargesParSupermarche(): array
    {
        $db = \Config\Database::connect();
        return $db->table('sorties as s')
            ->select('sm.nom as supermarche_nom, 
                      SUM(s.valeur_totale) as chiffre_affaires, 
                      SUM(s.valeur_totale) - SUM(s.quantite * s.cump_applique * tb.volume_litres) as marge_totale')
            ->join('supermarches as sm', 'sm.id = s.supermarche_id')
            ->join('types_bocaux as tb', 'tb.id = s.type_bocal_id')
            ->groupBy('sm.id, sm.nom')
            ->get()
            ->getResultArray();
    }
}