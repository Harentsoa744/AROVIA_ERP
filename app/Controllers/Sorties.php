<?php

namespace App\Controllers;

use App\Models\SortieModel;
use App\Models\StockProduitFiniModel;
use App\Models\TypeBocalModel;

class Sorties extends BaseController
{
    protected SortieModel $sortieModel;
    protected StockProduitFiniModel $stockPFModel;
    protected TypeBocalModel $typeBocalModel;

    public function __construct()
    {
        $this->sortieModel   = new SortieModel();
        $this->stockPFModel  = new StockProduitFiniModel();
        $this->typeBocalModel = new TypeBocalModel();
    }

    public function index()
{
    $filtres = [
        'type_bocal_id'      => $this->request->getGet('type_bocal_id'),
        'destinataire_type'  => $this->request->getGet('destinataire_type'),
        'date_debut'         => $this->request->getGet('date_debut'),
        'date_fin'           => $this->request->getGet('date_fin'),
    ];

    $data['sorties']      = $this->sortieModel->getSortiesAvecBocal($filtres);
    $data['stockPF']      = $this->stockPFModel->getStockAvecTypes();
    $data['typesBocaux']  = $this->typeBocalModel->findAll();
    $data['filtres']      = $filtres;

    return view('sorties/index', $data);
}

   public function new()
{
    $data['typesBocaux'] = $this->typeBocalModel->findAll();
    $data['stockPF']     = $this->stockPFModel->getStockAvecTypes();
    $data['stockMP']     = (new \App\Models\StockMatierePremiereModel())->getEtatStock();

    return view('sorties/new', $data);
}

    public function create()
    {
        $rules = [
            'type_bocal_id'       => 'required|is_natural_no_zero',
            'quantite'            => 'required|is_natural_no_zero',
            'destinataire_type'   => 'required|in_list[touriste,particulier,hotel]',
            'prix_vente_unitaire' => 'required|numeric|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $resultat = $this->sortieModel->enregistrerSortie(
            (int) $this->request->getPost('type_bocal_id'),
            (int) $this->request->getPost('quantite'),
            $this->request->getPost('destinataire_type'),
            $this->request->getPost('destinataire_nom'),
            (float) $this->request->getPost('prix_vente_unitaire')
        );

        if (! $resultat['succes']) {
            return redirect()->back()->withInput()->with('errors', [$resultat['message'] ?? 'Erreur lors de la sortie.']);
        }

        return redirect()->to('/sorties')->with('message', 'Sortie enregistrée pour ' . number_format($resultat['valeur_totale'], 2) . ' Ar.');
    }
}