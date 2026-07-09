<?php

namespace App\Models;

use CodeIgniter\Model;

class TransformationModel extends Model
{
    protected $table         = 'transformations';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'date_transformation', 'quantite_litres_utilisee', 'cump_applique', 
        'valeur_sortie', 'date_production', 'date_limite_vente', 
        'duree_conservation_mois', 'fournisseur_id'
    ];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    /**
     * Récupère l'historique des transformations avec le nombre de bocaux produits et le fournisseur d'origine.
     */
    public function getHistorique(): array
    {
        $db = \Config\Database::connect();

        if (! $db->tableExists('transformations')) {
            return [];
        }

        $transformations = $db->table('transformations as t')
            ->select('t.id, t.date_transformation, t.quantite_litres_utilisee, t.cump_applique, t.valeur_sortie, t.date_production, t.date_limite_vente, f.nom as fournisseur_nom')
            ->join('fournisseurs as f', 'f.id = t.fournisseur_id', 'left')
            ->orderBy('t.date_transformation', 'DESC')
            ->get()
            ->getResultArray();

        $details = [];
        if ($db->tableExists('transformations_detail') && $db->tableExists('types_bocaux')) {
            $details = $db->table('transformations_detail as td')
                ->select('td.transformation_id, td.quantite_produite, tb.nom, tb.volume_litres')
                ->join('types_bocaux as tb', 'tb.id = td.type_bocal_id', 'left')
                ->get()
                ->getResultArray();
        }

        $detailsParTransformation = [];
        foreach ($details as $detail) {
            $id = $detail['transformation_id'];
            if (! isset($detailsParTransformation[$id])) {
                $detailsParTransformation[$id] = [
                    'total_bocaux' => 0,
                    'volume_bocal_litres' => 0,
                    'bocal_noms' => [],
                ];
            }

            $detailsParTransformation[$id]['total_bocaux'] += (int) ($detail['quantite_produite'] ?? 0);
            $detailsParTransformation[$id]['volume_bocal_litres'] = max(
                $detailsParTransformation[$id]['volume_bocal_litres'],
                (float) ($detail['volume_litres'] ?? 0)
            );

            if (! empty($detail['nom'])) {
                $detailsParTransformation[$id]['bocal_noms'][] = $detail['nom'];
            }
        }

        foreach ($transformations as &$transformation) {
            $id = $transformation['id'];
            $detailsTransformation = $detailsParTransformation[$id] ?? [
                'total_bocaux' => 0,
                'volume_bocal_litres' => 0,
                'bocal_noms' => [],
            ];

            $transformation['total_bocaux'] = $detailsTransformation['total_bocaux'];
            $transformation['volume_bocal_litres'] = $detailsTransformation['volume_bocal_litres'];
            $transformation['bocal_noms'] = implode(', ', array_unique($detailsTransformation['bocal_noms']));
            if ($transformation['bocal_noms'] === '') {
                $transformation['bocal_noms'] = '—';
            }
        }

        return $transformations;
    }

    /**
     * Enregistre une transformation (mise en bocal).
     *
     * @param array $repartition Tableau [type_bocal_id => quantite_a_produire]
     * @param int $fournisseurId ID du fournisseur d'origine (matière première)
     */
    public function enregistrerTransformation(array $repartition, int $fournisseurId): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Verrouille l'état du stock matière première
        $stockMP = $db->query('SELECT * FROM stock_matiere_premiere LIMIT 1 FOR UPDATE')->getRowArray();

        // 2. Récupère le volume réel de chaque type de bocal concerné
        $typeBocalModel = new TypeBocalModel();
        $typesBocaux    = $typeBocalModel->whereIn('id', array_keys($repartition))->findAll();

        // 3. Calcule le volume total nécessaire (en litres) à partir des quantités demandées
        $volumeTotalNecessaire = 0;
        foreach ($typesBocaux as $type) {
            $quantiteDemandee       = $repartition[$type['id']] ?? 0;
            $volumeTotalNecessaire += $quantiteDemandee * $type['volume_litres'];
        }

        // 4. Vérifie que le stock matière première suffit
        if ($volumeTotalNecessaire <= 0 || $volumeTotalNecessaire > $stockMP['quantite_litres']) {
            $db->transRollback();
            return ['succes' => false, 'message' => 'Stock matière première insuffisant ou quantité invalide.'];
        }

        $cumpApplique = $stockMP['cump_actuel'];
        $valeurSortie = $volumeTotalNecessaire * $cumpApplique;

        // 5. Met à jour le stock matière première (le CUMP ne change JAMAIS ici)
        $db->table('stock_matiere_premiere')
           ->where('id', $stockMP['id'])
           ->update([
               'quantite_litres' => $stockMP['quantite_litres'] - $volumeTotalNecessaire,
               'valeur_stock'    => $stockMP['valeur_stock'] - $valeurSortie,
               'derniere_maj'    => date('Y-m-d H:i:s'),
           ]);

        // 6. Enregistre la transformation elle-même (avec dates et traçabilité fournisseur)
        $dateProduction = date('Y-m-d');
        $dateLimite = date('Y-m-d', strtotime('+24 months'));

        $db->table('transformations')->insert([
            'date_transformation'      => date('Y-m-d H:i:s'),
            'quantite_litres_utilisee' => $volumeTotalNecessaire,
            'cump_applique'            => $cumpApplique,
            'valeur_sortie'            => $valeurSortie,
            'fournisseur_id'           => $fournisseurId,
            'date_production'          => $dateProduction,
            'date_limite_vente'        => $dateLimite,
            'duree_conservation_mois'  => 24
        ]);
        $transformationId = $db->insertID();

        // 7. Enregistre le détail par type de bocal + incrémente le stock produit fini
        foreach ($repartition as $typeBocalId => $quantiteProduite) {
            if ($quantiteProduite <= 0) {
                continue;
            }

            $db->table('transformations_detail')->insert([
                'transformation_id' => $transformationId,
                'type_bocal_id'     => $typeBocalId,
                'quantite_produite' => $quantiteProduite,
            ]);

            $db->table('stock_produit_fini')
               ->set('quantite_disponible', 'quantite_disponible + ' . (int) $quantiteProduite, false)
               ->where('type_bocal_id', $typeBocalId)
               ->update();
        }

        $db->transComplete();

        return [
            'succes'                 => $db->transStatus(),
            'volume_total_utilise'   => $volumeTotalNecessaire,
            'valeur_sortie'          => $valeurSortie,
        ];
    }
}