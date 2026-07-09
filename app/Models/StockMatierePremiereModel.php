<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMatierePremiereModel extends Model
{
    protected $table         = 'stock_matiere_premiere';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['quantite_litres', 'valeur_stock', 'cump_actuel', 'derniere_maj', 'seuil_alerte'];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    // Retourne l'état actuel du stock (une seule ligne dans la table)
    public function getEtatStock(): array
    {
        return $this->first();
    }

    /**
     * Enregistre une nouvelle entrée de matière première :
     * - calcule le nouveau CUMP
     * - met à jour l'état du stock
     * - insère la ligne d'historique
     * Tout est protégé par une transaction + verrouillage de ligne (FOR UPDATE)
     * pour éviter les incohérences si deux entrées arrivent en même temps.
     */
    public function enregistrerEntree(int $fournisseurId, float $quantite, float $prixUnitaire): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Verrouille la ligne de stock pendant le calcul
        $stock = $db->query('SELECT * FROM stock_matiere_premiere LIMIT 1 FOR UPDATE')->getRowArray();

        $valeurEntree     = $quantite * $prixUnitaire;
        $nouvelleQuantite = $stock['quantite_litres'] + $quantite;
        $nouvelleValeur   = $stock['valeur_stock'] + $valeurEntree;
        $nouveauCump      = $nouvelleQuantite > 0 ? $nouvelleValeur / $nouvelleQuantite : 0;

        // Historique de l'entrée (jamais modifié après coup)
        $db->table('entrees_matiere_premiere')->insert([
            'fournisseur_id'    => $fournisseurId,
            'numero_lot'        => 'MP-' . date('Y') . '-' . str_pad((string) ($this->countEntrees() + 1), 4, '0', STR_PAD_LEFT),
            'date_entree'       => date('Y-m-d H:i:s'),
            'quantite_litres'   => $quantite,
            'prix_unitaire'     => $prixUnitaire,
            'valeur_totale'     => $valeurEntree,
            'cump_apres_entree' => $nouveauCump,
        ]);

        // Mise à jour de l'état courant du stock
        $db->table('stock_matiere_premiere')
           ->where('id', $stock['id'])
           ->update([
               'quantite_litres' => $nouvelleQuantite,
               'valeur_stock'    => $nouvelleValeur,
               'cump_actuel'     => $nouveauCump,
               'derniere_maj'    => date('Y-m-d H:i:s'),
           ]);

        $db->transComplete();

        return $db->transStatus();
    }

    private function countEntrees(): int
    {
        $db = \Config\Database::connect();
        return (int) $db->table('entrees_matiere_premiere')->countAllResults();
    }
}