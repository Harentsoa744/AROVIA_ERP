<?php

namespace App\Controllers;

use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;
use App\Models\FournisseurModel;
use App\Models\EmployeModel;

class Home extends BaseController
{
    public function index(): string
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();
        $fournisseurModel = new FournisseurModel();
        $employeModel = new EmployeModel();

        $data['stockMP']        = $stockMPModel->getEtatStock();
        $data['stockPF']        = $stockPFModel->getStockAvecTypes();
        $data['nbFournisseurs'] = $fournisseurModel->countAll();
        $data['nbEmployes']     = count($employeModel->getActifs());

        $data['totalBocaux'] = array_sum(array_column($data['stockPF'], 'quantite_disponible'));

        // 1. Alertes de stock bas
        $alertesStock = [];
        if (($data['stockMP']['quantite_litres'] ?? 0) < ($data['stockMP']['seuil_alerte'] ?? 10)) {
            $alertesStock[] = [
                'type' => 'MP',
                'message' => 'Stock de miel brut critique : ' . number_format($data['stockMP']['quantite_litres'], 2) . ' L restant (seuil de ' . number_format($data['stockMP']['seuil_alerte'], 2) . ' L).',
                'niveau' => 'danger'
            ];
        }

        foreach ($data['stockPF'] as $bocal) {
            if (($bocal['quantite_disponible'] ?? 0) < ($bocal['seuil_alerte'] ?? 20)) {
                $alertesStock[] = [
                    'type' => 'PF',
                    'message' => 'Stock de bocaux ' . esc($bocal['nom']) . ' bas : ' . $bocal['quantite_disponible'] . ' unité(s) (seuil de ' . $bocal['seuil_alerte'] . ').',
                    'niveau' => 'danger'
                ];
            }
        }

        // 2. Alertes de péremption (DLC)
        $db = \Config\Database::connect();
        $date30jours = date('Y-m-d', strtotime('+30 days'));
        $dateAujourdhui = date('Y-m-d');

        $transformationsExpirant = $db->table('transformations t')
            ->select('t.id, t.date_limite_vente, t.date_production, f.nom as fournisseur_nom')
            ->join('fournisseurs f', 'f.id = t.fournisseur_id', 'left')
            ->where('t.date_limite_vente <=', $date30jours)
            ->get()
            ->getResultArray();

        $alertesPeremption = [];
        foreach ($transformationsExpirant as $t) {
            $estExpired = ($t['date_limite_vente'] <= $dateAujourdhui);
            $joursRestants = (int) ((strtotime($t['date_limite_vente']) - strtotime($dateAujourdhui)) / 86400);

            $alertesPeremption[] = [
                'id' => $t['id'],
                'fournisseur' => $t['fournisseur_nom'] ?? 'Inconnu',
                'date_limite' => $t['date_limite_vente'],
                'jours_restants' => $joursRestants,
                'est_expire' => $estExpired,
                'niveau' => $estExpired ? 'danger' : 'warning'
            ];
        }

        $data['alertesStock'] = $alertesStock;
        $data['alertesPeremption'] = $alertesPeremption;

        return view('home', $data);
    }

    public function dashboard(): string
    {
        return $this->index();
    }
}