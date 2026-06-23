<?php

namespace App\Controllers;

use App\Models\EntreeMatierePremiereModel;
use App\Models\StockMatierePremiereModel;
use App\Models\FournisseurModel;

class EntreesMatierePremiere extends BaseController
{
    protected EntreeMatierePremiereModel $entreeModel;
    protected StockMatierePremiereModel $stockModel;
    protected FournisseurModel $fournisseurModel;

    public function __construct()
    {
        $this->entreeModel      = new EntreeMatierePremiereModel();
        $this->stockModel       = new StockMatierePremiereModel();
        $this->fournisseurModel = new FournisseurModel();
    }

    // Liste de l'historique des entrées + état actuel du stock
  public function index()
{
    $filtres = [
        'fournisseur_id' => $this->request->getGet('fournisseur_id'),
        'date_debut'     => $this->request->getGet('date_debut'),
        'date_fin'       => $this->request->getGet('date_fin'),
    ];

    $data['entrees']      = $this->entreeModel->getEntreesAvecFournisseur($filtres);
    $data['stock']        = $this->stockModel->getEtatStock();
    $data['fournisseurs'] = $this->fournisseurModel->findAll();
    $data['filtres']      = $filtres;

    return view('entrees_matiere_premiere/index', $data);
}

    // Formulaire d'ajout
    public function new()
    {
        $data['fournisseurs'] = $this->fournisseurModel->findAll();

        return view('entrees_matiere_premiere/new', $data);
    }

    // Traitement du formulaire
    public function create()
    {
        $rules = [
            'fournisseur_id' => 'required|is_natural_no_zero',
            'quantite'       => 'required|numeric|greater_than[0]',
            'prix_unitaire'  => 'required|numeric|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $succes = $this->stockModel->enregistrerEntree(
            (int) $this->request->getPost('fournisseur_id'),
            (float) $this->request->getPost('quantite'),
            (float) $this->request->getPost('prix_unitaire')
        );

        if (! $succes) {
            return redirect()->back()->withInput()->with('errors', ['Une erreur est survenue lors de l\'enregistrement.']);
        }

        return redirect()->to('/entrees-matiere-premiere')->with('message', 'Entrée enregistrée avec succès.');
    }
}