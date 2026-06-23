<?php

namespace App\Controllers;

use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;

class ValeurStock extends BaseController
{
    public function index()
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();

        $stockMP = $stockMPModel->getEtatStock();
        $stockPF = $stockPFModel->getStockAvecTypes();

        $valeurComptablePF = 0;
        $valeurVentePF     = 0;

        foreach ($stockPF as &$bocal) {
            $coutUnitaire     = $stockMP['cump_actuel'] * $bocal['volume_litres'];
            $bocal['cout_unitaire']    = $coutUnitaire;
            $bocal['valeur_comptable'] = $coutUnitaire * $bocal['quantite_disponible'];
            $bocal['valeur_vente']     = ($bocal['prix_vente'] ?? 0) * $bocal['quantite_disponible'];

            $valeurComptablePF += $bocal['valeur_comptable'];
            $valeurVentePF     += $bocal['valeur_vente'];
        }
        unset($bocal);

        $data['stockMP']             = $stockMP;
        $data['stockPF']             = $stockPF;
        $data['valeurComptablePF']   = $valeurComptablePF;
        $data['valeurVentePF']       = $valeurVentePF;
        $data['valeurTotaleComptable'] = $stockMP['valeur_stock'] + $valeurComptablePF;

        return view('valeur_stock/index', $data);
    }
}