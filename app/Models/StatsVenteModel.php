<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\FluxFinancier;

class StatsVenteModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $returnType       = FluxFinancier::class;
    protected $allowedFields    = ['type', 'categorie', 'montant', 'description', 'date_transaction'];
    protected $useTimestamps    = true;

    // --- ENCAISSEMENTS (Depuis la table sorties) ---

    public function getEncaissements()
    {
        return $this->db->table('sorties s')
            ->select('s.*, t.nom as bocal_nom, t.volume_litres')
            ->join('types_bocaux t', 't.id = s.type_bocal_id')
            ->orderBy('s.date_sortie', 'DESC')
            ->get()
            ->getResultArray();
    }

    // --- DÉCAISSEMENTS & AUTRES RECETTES (Depuis la table transactions) ---

    public function getTransactionsByType(string $type)
    {
        return $this->where('type', $type)
                    ->orderBy('date_transaction', 'DESC')
                    ->findAll();
    }

    // --- TABLEAU DE BORD (Calculs combinés PostgreSQL) ---

    /**
     * Solde disponible aujourd'hui — ignore le filtre de dates (toujours global)
     */
    public function getSoldeDisponible()
    {
        $ventes = $this->db->table('sorties')->selectSum('valeur_totale', 'total')->get()->getRow();
        $totalVentes = $ventes->total ?? 0;

        $transactions = $this->select("
            SUM(CASE WHEN type = 'recette' THEN montant ELSE 0 END) as total_recettes,
            SUM(CASE WHEN type = 'depense' THEN montant ELSE 0 END) as total_depenses
        ")->first();

        $totalRecettes = $transactions->total_recettes ?? 0;
        $totalDepenses = $transactions->total_depenses ?? 0;

        return ($totalVentes + $totalRecettes) - $totalDepenses;
    }

    /**
     * Somme des entrées (Ventes + Recettes) sur la plage de dates donnée
     */
    public function getTotalEntreesMois(string $dateDebut = '', string $dateFin = '')
    {
        if (empty($dateDebut)) $dateDebut = date('Y-m-01');
        if (empty($dateFin))   $dateFin   = date('Y-m-d');

        $debutTs = $dateDebut . ' 00:00:00';
        $finTs   = $dateFin   . ' 23:59:59';

        $ventesMois = $this->db->table('sorties')
            ->selectSum('valeur_totale', 'total')
            ->where('date_sortie >=', $debutTs)
            ->where('date_sortie <=', $finTs)
            ->get()->getRow()->total ?? 0;

        $recettesMois = $this->db->table('transactions')
            ->selectSum('montant', 'total')
            ->where('type', 'recette')
            ->where('date_transaction >=', $debutTs)
            ->where('date_transaction <=', $finTs)
            ->get()->getRow()->total ?? 0;

        return (float)$ventesMois + (float)$recettesMois;
    }

    /**
     * Somme des dépenses sur la plage de dates donnée
     */
    public function getTotalSortiesMois(string $dateDebut = '', string $dateFin = '')
    {
        if (empty($dateDebut)) $dateDebut = date('Y-m-01');
        if (empty($dateFin))   $dateFin   = date('Y-m-d');

        $depensesMois = $this->db->table('transactions')
            ->selectSum('montant', 'total')
            ->where('type', 'depense')
            ->where('date_transaction >=', $dateDebut . ' 00:00:00')
            ->where('date_transaction <=', $dateFin   . ' 23:59:59')
            ->get()->getRow()->total ?? 0;

        return (float)$depensesMois;
    }

    /**
     * Données consolidées par mois pour le graphique — filtrées sur la plage donnée
     */
    public function getDonneesGraphiqueAnnuel(string $dateDebut = '', string $dateFin = '')
    {
        if (empty($dateDebut)) $dateDebut = date('Y-01-01');
        if (empty($dateFin))   $dateFin   = date('Y-12-31');

        $debutTs = $dateDebut . ' 00:00:00';
        $finTs   = $dateFin   . ' 23:59:59';

        $sql = "
            SELECT
                mois,
                SUM(total_recette) as total_entrees,
                SUM(total_depense) as total_sorties
            FROM (
                SELECT
                    EXTRACT(MONTH FROM date_sortie)::int as mois,
                    SUM(valeur_totale) as total_recette,
                    0 as total_depense
                FROM sorties
                WHERE date_sortie >= :debut:
                  AND date_sortie <= :fin:
                GROUP BY mois

                UNION ALL

                SELECT
                    EXTRACT(MONTH FROM date_transaction)::int as mois,
                    SUM(CASE WHEN type = 'recette' THEN montant ELSE 0 END) as total_recette,
                    SUM(CASE WHEN type = 'depense' THEN montant ELSE 0 END) as total_depense
                FROM transactions
                WHERE date_transaction >= :debut:
                  AND date_transaction <= :fin:
                GROUP BY mois
            ) as flux_unifies
            GROUP BY mois
            ORDER BY mois ASC;
        ";

        return $this->db->query($sql, ['debut' => $debutTs, 'fin' => $finTs])->getResultArray();
    }
}