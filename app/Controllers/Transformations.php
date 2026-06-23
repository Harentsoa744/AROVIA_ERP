<?php

namespace App\Controllers;

use App\Models\TransformationModel;
use App\Models\StockMatierePremiereModel;
use App\Models\StockProduitFiniModel;
use App\Models\TypeBocalModel;

class Transformations extends BaseController
{
    protected TransformationModel $transformationModel;
    protected StockMatierePremiereModel $stockMPModel;
    protected StockProduitFiniModel $stockPFModel;
    protected TypeBocalModel $typeBocalModel;

    public function __construct()
    {
        $this->transformationModel = new TransformationModel();
        $this->stockMPModel        = new StockMatierePremiereModel();
        $this->stockPFModel        = new StockProduitFiniModel();
        $this->typeBocalModel      = new TypeBocalModel();
    }

    public function index()
    {
        $data['stockMP'] = $this->stockMPModel->getEtatStock();
        $data['stockPF'] = $this->stockPFModel->getStockAvecTypes();

        return view('transformations/index', $data);
    }

    public function new()
    {
        $data['stockMP']     = $this->stockMPModel->getEtatStock();
        $data['typesBocaux'] = $this->typeBocalModel->findAll();

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

        $resultat = $this->transformationModel->enregistrerTransformation($repartition);

        if (! $resultat['succes']) {
            return redirect()->back()->withInput()->with('errors', [$resultat['message'] ?? 'Erreur lors de la transformation.']);
        }

        return redirect()->to('/transformations')
            ->with('message', 'Transformation enregistrée : ' . number_format($resultat['volume_total_utilise'], 2) . ' L utilisés.');
    }
}