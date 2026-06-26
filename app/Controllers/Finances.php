<?php

namespace App\Controllers;

use App\Models\FinanceModel;

class Finances extends BaseController
{
    protected $financeModel;

    public function __construct()
    {
        $this->financeModel = new FinanceModel();
    }

    // -------------------------------------------------------
    // Recettes & Dépenses — liste + formulaire d'ajout
    // -------------------------------------------------------
    public function index()
    {
        $type      = $this->request->getGet('type');
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        $data['transactions'] = $this->financeModel->getTransactions($type, $dateDebut, $dateFin);
        $data['solde']        = $this->financeModel->getSolde();
        $data['filtre_type']  = $type;

        return view('finances/index', $data);
    }

    // -------------------------------------------------------
    // Enregistrer une nouvelle transaction
    // -------------------------------------------------------
    public function store()
    {
        $this->financeModel->insert([
            'type'             => $this->request->getPost('type'),
            'categorie'        => $this->request->getPost('categorie'),
            'montant'          => $this->request->getPost('montant'),
            'description'      => $this->request->getPost('description'),
            'date_transaction' => $this->request->getPost('date_transaction'),
        ]);

        return redirect()->to('/finances');
    }

    // -------------------------------------------------------
    // Supprimer une transaction
    // -------------------------------------------------------
    public function delete($id)
    {
        $this->financeModel->delete($id);
        return redirect()->to('/finances');
    }

    // -------------------------------------------------------
    // Trésorerie — solde + mouvements récents
    // -------------------------------------------------------
    public function tresorerie()
    {
        $dateDebut = $this->request->getGet('date_debut');
        $dateFin   = $this->request->getGet('date_fin');

        $data['solde']      = $this->financeModel->getSolde();
        $data['mouvements'] = $this->financeModel->getTransactions(null, $dateDebut, $dateFin);
        $data['alerte']     = $data['solde'] < 0;

        return view('finances/tresorerie', $data);
    }

    // -------------------------------------------------------
    // Rapports & Analyses
    // -------------------------------------------------------
    public function rapport()
    {
        $dateDebut = $this->request->getGet('date_debut') ?? date('Y-m-01');
        $dateFin   = $this->request->getGet('date_fin')   ?? date('Y-m-d');

        $data['totaux']    = $this->financeModel->getTotauxParPeriode($dateDebut, $dateFin);
        $data['evolution'] = $this->financeModel->getEvolutionMensuelle();
        $data['dateDebut'] = $dateDebut;
        $data['dateFin']   = $dateFin;

        return view('finances/rapport', $data);
    }
}