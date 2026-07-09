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

        $data['entreesParDate']         = $entreeModel->getStatistiquesParDate();
        $data['entreesParFournisseur']  = $entreeModel->getStatistiquesParFournisseur();
        $data['sortiesParDate']         = $sortieModel->getStatistiquesParDate();
        $data['sortiesParSupermarche']  = $sortieModel->getStatistiquesParSupermarche();
        $data['margesParSupermarche']   = $sortieModel->getMargesParSupermarche();

        $data['totalLitresEntre']   = array_sum(array_column($data['entreesParDate'], 'total_litres'));
        $data['totalBocauxVendus']  = array_sum(array_column($data['sortiesParDate'], 'total_quantite'));
        $data['fournisseurPrincipal'] = $data['entreesParFournisseur'][0]['fournisseur_nom'] ?? 'Aucun';
        $data['tauxVente'] = $data['totalLitresEntre'] > 0
            ? round(($data['totalBocauxVendus'] / $data['totalLitresEntre']) * 100, 2)
            : 0;
        $data['stockEstime'] = max(0, $data['totalLitresEntre'] - $data['totalBocauxVendus']);

        // Calcul global des marges
        $data['totalCA']    = array_sum(array_column($data['margesParSupermarche'], 'chiffre_affaires'));
        $data['totalMarge'] = array_sum(array_column($data['margesParSupermarche'], 'marge_totale'));
        $data['tauxMargeGlobal'] = $data['totalCA'] > 0
            ? round(($data['totalMarge'] / $data['totalCA']) * 100, 2)
            : 0;

        return view('statistiques/index', $data);
    }
}