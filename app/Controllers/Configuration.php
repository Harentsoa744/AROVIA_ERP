<?php

namespace App\Controllers;

use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;

class Configuration extends BaseController
{
    public function index()
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();

        $data['stockMP'] = $stockMPModel->getEtatStock();
        $data['stockPF'] = $stockPFModel->getStockAvecTypes();

        return view('configuration/index', $data);
    }

    public function update()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Mise à jour seuil MP
        $seuilMP = (float) $this->request->getPost('seuil_mp');
        $db->table('stock_matiere_premiere')
           ->update(['seuil_alerte' => $seuilMP]);

        // 2. Mise à jour seuils PF
        $stockPFModel = new StockProduitFiniModel();
        $stockPF = $stockPFModel->getStockAvecTypes();

        foreach ($stockPF as $bocal) {
            $seuilBocal = (int) $this->request->getPost('seuil_bocal_' . $bocal['type_bocal_id']);
            $db->table('stock_produit_fini')
               ->where('type_bocal_id', $bocal['type_bocal_id'])
               ->update(['seuil_alerte' => $seuilBocal]);
        }

        $db->transComplete();

        if ($db->transStatus()) {
            return redirect()->to('/configuration')->with('message', 'Configuration des seuils mise à jour avec succès.');
        }

        return redirect()->to('/configuration')->with('errors', ['Erreur lors de la mise à jour des seuils.']);
    }
}
