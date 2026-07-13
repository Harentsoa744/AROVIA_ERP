<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceModel extends Model
{
    protected $table = 'mouvements_financiers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'type',
        'categorie',
        'montant',
        'description',
        'date_transaction',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------
    // Toutes les transactions (avec filtre optionnel)
    // -------------------------------------------------------
    public function getTransactions($type = null, $dateDebut = null, $dateFin = null)
    {
        $builder = $this->builder();

        if ($type) {
            $builder->where('type', $type);
        }
        if ($dateDebut) {
            $builder->where('date_transaction >=', $dateDebut);
        }
        if ($dateFin) {
            $builder->where('date_transaction <=', $dateFin);
        }

        return $builder->orderBy('date_transaction', 'DESC')->get()->getResultArray();
    }

    // -------------------------------------------------------
    // Solde actuel (total recettes - total dépenses)
    // -------------------------------------------------------
    public function getSolde()
    {
        $recettes = $this->where('type', 'recette')->selectSum('montant')->first()['montant'] ?? 0;
        $depenses = $this->where('type', 'depense')->selectSum('montant')->first()['montant'] ?? 0;

        return $recettes - $depenses;
    }

    // -------------------------------------------------------
    // Total recettes et dépenses sur une période
    // -------------------------------------------------------
    public function getTotauxParPeriode($dateDebut, $dateFin)
    {
        $recettes = $this->where('type', 'recette')
                         ->where('date_transaction >=', $dateDebut)
                         ->where('date_transaction <=', $dateFin)
                         ->selectSum('montant')
                         ->first()['montant'] ?? 0;

        $depenses = $this->where('type', 'depense')
                         ->where('date_transaction >=', $dateDebut)
                         ->where('date_transaction <=', $dateFin)
                         ->selectSum('montant')
                         ->first()['montant'] ?? 0;

        return [
            'recettes' => $recettes,
            'depenses' => $depenses,
            'benefice' => $recettes - $depenses,
        ];
    }

    // -------------------------------------------------------
    // Évolution mensuelle (pour graphique)
    // -------------------------------------------------------
    public function getEvolutionMensuelle()
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT 
                DATE_FORMAT(date_transaction, '%Y-%m') AS mois,
                type,
                SUM(montant) AS total
            FROM mouvements_financiers
            GROUP BY mois, type
            ORDER BY mois ASC
        ");

        return $query->getResultArray();
    }

    // -------------------------------------------------------
    // Mouvements récents (pour la trésorerie)
    // -------------------------------------------------------
    public function getMouvementsRecents($limite = 10)
    {
        return $this->orderBy('date_transaction', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }
}