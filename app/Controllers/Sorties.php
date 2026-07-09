<?php

namespace App\Controllers;

use App\Models\SortieModel;
use App\Models\StockProduitFiniModel;
use App\Models\TypeBocalModel;
use App\Models\SupermarcheModel;

class Sorties extends BaseController
{
    protected SortieModel $sortieModel;
    protected StockProduitFiniModel $stockPFModel;
    protected TypeBocalModel $typeBocalModel;
    protected SupermarcheModel $supermarcheModel;

    public function __construct()
    {
        $this->sortieModel      = new SortieModel();
        $this->stockPFModel     = new StockProduitFiniModel();
        $this->typeBocalModel   = new TypeBocalModel();
        $this->supermarcheModel = new SupermarcheModel();
    }

    public function index()
    {
        $filtres = [
            'type_bocal_id'   => $this->request->getGet('type_bocal_id'),
            'supermarche_id'  => $this->request->getGet('supermarche_id'),
            'date_debut'      => $this->request->getGet('date_debut'),
            'date_fin'        => $this->request->getGet('date_fin'),
        ];

        $data['sorties']      = $this->sortieModel->getSortiesAvecBocal($filtres);
        $data['stockPF']      = $this->stockPFModel->getStockAvecTypes();
        $data['typesBocaux']  = $this->typeBocalModel->findAll();
        $data['supermarches'] = $this->supermarcheModel->findAll();
        $data['filtres']      = $filtres;

        // Fetch CUMP for real-time cost / margin calculations on frontend
        $data['stockMP']      = (new \App\Models\StockMatierePremiereModel())->getEtatStock();

        return view('sorties/index', $data);
    }

    public function new()
    {
        $data['typesBocaux']  = $this->typeBocalModel->findAll();
        $data['stockPF']      = $this->stockPFModel->getStockAvecTypes();
        $data['stockMP']      = (new \App\Models\StockMatierePremiereModel())->getEtatStock();
        $data['supermarches'] = $this->supermarcheModel->findAll();

        return view('sorties/new', $data);
    }

    public function create()
    {
        $rules = [
            'type_bocal_id'       => 'required|is_natural_no_zero',
            'quantite'            => 'required|is_natural_no_zero',
            'supermarche_id'      => 'required|is_natural_no_zero',
            'prix_vente_unitaire' => 'required|numeric|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $resultat = $this->sortieModel->enregistrerSortie(
            (int) $this->request->getPost('type_bocal_id'),
            (int) $this->request->getPost('quantite'),
            (int) $this->request->getPost('supermarche_id'),
            (float) $this->request->getPost('prix_vente_unitaire')
        );

        if (! $resultat['succes']) {
            return redirect()->back()->withInput()->with('errors', [$resultat['message'] ?? 'Erreur lors de la sortie.']);
        }

        return redirect()->to('/sorties')->with('message', 'Vente enregistrée pour ' . number_format($resultat['valeur_totale'], 2) . ' Ar.');
    }

    /**
     * GET /sorties/facture/(:num)
     * Affiche la facture pour une sortie donnée
     */
    public function facture(int $id)
    {
        $sortie = $this->sortieModel->select('sorties.*, types_bocaux.nom as bocal_nom, types_bocaux.volume_litres as bocal_volume, supermarches.nom as supermarche_nom, supermarches.contact as supermarche_contact')
                                    ->join('types_bocaux', 'types_bocaux.id = sorties.type_bocal_id')
                                    ->join('supermarches', 'supermarches.id = sorties.supermarche_id')
                                    ->where('sorties.id', $id)
                                    ->first();

        if (! $sortie) {
            return redirect()->to('/sorties')->with('error', 'Sortie introuvable.');
        }

        $data = [
            'titre'  => 'Facture N°' . str_pad((string) $sortie['id'], 5, '0', STR_PAD_LEFT),
            'sortie' => $sortie,
        ];

        return view('sorties/facture', $data);
    }

    /**
     * GET /sorties/imprimer/(:num)
     * Affiche la facture en mode impression
     */
    public function imprimer(int $id)
    {
        $sortie = $this->sortieModel->select('sorties.*, types_bocaux.nom as bocal_nom, types_bocaux.volume_litres as bocal_volume, supermarches.nom as supermarche_nom, supermarches.contact as supermarche_contact')
                                    ->join('types_bocaux', 'types_bocaux.id = sorties.type_bocal_id')
                                    ->join('supermarches', 'supermarches.id = sorties.supermarche_id')
                                    ->where('sorties.id', $id)
                                    ->first();

        if (! $sortie) {
            return redirect()->to('/sorties')->with('error', 'Sortie introuvable.');
        }

        $data = [
            'titre'  => 'Facture N°' . str_pad((string) $sortie['id'], 5, '0', STR_PAD_LEFT),
            'sortie' => $sortie,
        ];

        return view('sorties/facture_impression', $data);
    }
}
