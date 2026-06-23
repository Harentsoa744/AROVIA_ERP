<?php

namespace App\Models;

use CodeIgniter\Model;

class SortieModel extends Model
{
    protected $table         = 'sorties';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'date_sortie', 'type_bocal_id', 'quantite',
        'destinataire_type', 'destinataire_nom',
        'prix_vente_unitaire', 'valeur_totale',
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getSortiesAvecBocal(array $filtres = [])
{
    $builder = $this->select('sorties.*, types_bocaux.nom as bocal_nom')
                ->join('types_bocaux', 'types_bocaux.id = sorties.type_bocal_id');

    if (! empty($filtres['type_bocal_id'])) {
        $builder->where('sorties.type_bocal_id', $filtres['type_bocal_id']);
    }

    if (! empty($filtres['destinataire_type'])) {
        $builder->where('sorties.destinataire_type', $filtres['destinataire_type']);
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
     * décrémente le stock produit fini, enregistre la sortie.
     * Protégé par transaction + verrouillage de ligne.
     */
    public function enregistrerSortie(int $typeBocalId, int $quantite, string $destinataireType, ?string $destinataireNom, float $prixUnitaire): array
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
            'destinataire_type'    => $destinataireType,
            'destinataire_nom'     => $destinataireNom,
            'prix_vente_unitaire'  => $prixUnitaire,
            'valeur_totale'        => $valeurTotale,
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

// Pour le camembert : total des bocaux vendus, groupé par destinataire
public function getStatistiquesParDestinataire(): array
{
    return $this->select('destinataire_type, SUM(quantite) as total_quantite')
                ->groupBy('destinataire_type')
                ->findAll();
}


}