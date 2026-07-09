<?php

namespace App\Controllers;

use App\Models\TransformationModel;
use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;
use App\Models\TypeBocalModel;
use App\Models\FournisseurModel;

class Transformations extends BaseController
{
    protected TransformationModel $transformationModel;
    protected StockMatierePremiereModel $stockMPModel;
    protected StockProduitFiniModel $stockPFModel;
    protected TypeBocalModel $typeBocalModel;
    protected FournisseurModel $fournisseurModel;

    public function __construct()
    {
        $this->transformationModel = new TransformationModel();
        $this->stockMPModel        = new StockMatierePremiereModel();
        $this->stockPFModel        = new StockProduitFiniModel();
        $this->typeBocalModel      = new TypeBocalModel();
        $this->fournisseurModel    = new FournisseurModel();
    }

    public function index()
    {
        $transformations = $this->transformationModel->getHistorique();

        $data['stockMP'] = $this->stockMPModel->getEtatStock();
        $data['stockPF'] = $this->stockPFModel->getStockAvecTypes();
        $data['transformations'] = $transformations;
        $data['totalTransformations'] = count($transformations);
        $data['litresTransformes'] = array_sum(array_column($transformations, 'quantite_litres_utilisee'));
        $data['bocauxProduits'] = array_sum(array_column($transformations, 'total_bocaux'));
        $data['perteTotale'] = array_reduce($transformations, function ($carry, $item) {
            $volumeProduit = ($item['total_bocaux'] ?? 0) * ($item['volume_bocal_litres'] ?? 0);
            return $carry + max(0, ($item['quantite_litres_utilisee'] ?? 0) - $volumeProduit);
        }, 0);
        $data['tauxPerte'] = $data['litresTransformes'] > 0
            ? round(($data['perteTotale'] / $data['litresTransformes']) * 100, 2)
            : 0;
        
        $data['typesBocaux'] = $this->typeBocalModel->findAll();

        return view('transformations/index', $data);
    }

    public function new()
    {
        $data['stockMP']      = $this->stockMPModel->getEtatStock();
        $data['typesBocaux']  = $this->typeBocalModel->findAll();
        $data['fournisseurs'] = $this->fournisseurModel->findAll();

        return view('transformations/new', $data);
    }

    public function create()
    {
        $typesBocaux = $this->typeBocalModel->findAll();

        // Construit le tableau de répartition [type_bocal_id => quantite] depuis le formulaire
        $repartition = [];
        foreach ($typesBocaux as $type) {
            $quantite = (int) $this->request->getPost('quantite_' . $type['id']);
            if ($quantite > 0) {
                $repartition[$type['id']] = $quantite;
            }
        }

        if (empty($repartition)) {
            return redirect()->back()->with('errors', ['Veuillez indiquer au moins une quantité de bocal à produire.']);
        }

        $fournisseurId = (int) $this->request->getPost('fournisseur_id');
        if ($fournisseurId <= 0) {
            return redirect()->back()->withInput()->with('errors', ['Veuillez sélectionner un fournisseur d\'origine pour assurer la traçabilité.']);
        }

        $resultat = $this->transformationModel->enregistrerTransformation($repartition, $fournisseurId);

        if (! $resultat['succes']) {
            return redirect()->back()->withInput()->with('errors', [$resultat['message'] ?? 'Erreur lors de la transformation.']);
        }

        return redirect()->to('/transformations')
            ->with('message', 'Transformation enregistrée : ' . number_format($resultat['volume_total_utilise'], 2) . ' L utilisés.');
    }
}