<?php

namespace App\Models;

use CodeIgniter\Model;

class TransformationModel extends Model
{
    protected $table         = 'transformations';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['date_transformation', 'quantite_litres_utilisee', 'cump_applique', 'valeur_sortie'];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    /**
     * Enregistre une transformation (mise en bocal).
     *
     * @param array $repartition Tableau [type_bocal_id => quantite_a_produire]
     *
     * Le volume total nécessaire est calculé côté serveur à partir du volume
     * réel de chaque type de bocal (jamais fait confiance au calcul du frontend).
     */
    public function enregistrerTransformation(array $repartition): array
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

        // 6. Enregistre la transformation elle-même
        $db->table('transformations')->insert([
            'date_transformation'      => date('Y-m-d H:i:s'),
            'quantite_litres_utilisee' => $volumeTotalNecessaire,
            'cump_applique'            => $cumpApplique,
            'valeur_sortie'            => $valeurSortie,
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