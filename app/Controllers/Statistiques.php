<?php

namespace App\Controllers;

use App\Models\EntreeMatierePremiereModel;
use App\Models\SortieModel;

class Statistiques extends BaseController
{
    public function index()
    {
        $entreeModel = new EntreeMatierePremiereModel();
        $sortieModel = new SortieModel();

        $data['entreesParDate']        = $entreeModel->getStatistiquesParDate();
        $data['entreesParFournisseur'] = $entreeModel->getStatistiquesParFournisseur();
        $data['sortiesParDate']        = $sortieModel->getStatistiquesParDate();
        $data['sortiesParDestinataire'] = $sortieModel->getStatistiquesParDestinataire();

        return view('statistiques/index', $data);
    }
}