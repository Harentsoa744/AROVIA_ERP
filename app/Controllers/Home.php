<?php

namespace App\Controllers;

use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;
use App\Models\FournisseurModel;

class Home extends BaseController
{
    public function index(): string
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();
        $fournisseurModel = new FournisseurModel();

        $data['stockMP']        = $stockMPModel->getEtatStock();
        $data['stockPF']        = $stockPFModel->getStockAvecTypes();
        $data['nbFournisseurs'] = $fournisseurModel->countAll();

        $data['totalBocaux'] = array_sum(array_column($data['stockPF'], 'quantite_disponible'));

        return view('home', $data);
    }

    public function dashboard(): string
    {
        $stockMPModel = new StockMatierePremiereModel();
        $stockPFModel = new StockProduitFiniModel();
        $fournisseurModel = new FournisseurModel();

        $data['stockMP']        = $stockMPModel->getEtatStock();
        $data['stockPF']        = $stockPFModel->getStockAvecTypes();
        $data['nbFournisseurs'] = $fournisseurModel->countAll();

        $data['totalBocaux'] = array_sum(array_column($data['stockPF'], 'quantite_disponible'));

        return view('dashboard', $data);
    }
}