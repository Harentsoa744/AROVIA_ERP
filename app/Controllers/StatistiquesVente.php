<?php

namespace App\Controllers;

use App\Models\StatsVenteModel;

class StatistiquesVente extends BaseController
{
    protected $statsModel;

    public function __construct()
    {
        $this->statsModel = new StatsVenteModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // 1. Récupération des dates du formulaire GET
        $dateDebutRaw = $this->request->getGet('date_debut');
        $dateFinRaw   = $this->request->getGet('date_fin');

        // --- LOGIQUE PAR DÉFAUT : VUE GLOBALE ---
        if (!empty($dateDebutRaw)) {
            $dateDebut = $dateDebutRaw;
        } else {
            // On cherche la toute première date d'entrée pour démarrer au tout début de ton activité
            $premiereEntree = $db->table('entrees_matiere_premiere')->selectMin('date_entree')->get()->getRowArray();
            $dateDebut = (!empty($premiereEntree['date_entree'])) ? date('Y-m-d', strtotime($premiereEntree['date_entree'])) : date('Y-m-d', strtotime('-30 days'));
        }

        if (!empty($dateFinRaw)) {
            $dateFin = $dateFinRaw;
        } else {
            // Par défaut, on s'arrête aujourd'hui
            $dateFin = date('Y-m-d');
        }
        // ----------------------------------------

        // 2. Requête Entrées (Miel)
        $builderEntrees = $db->table('entrees_matiere_premiere e')
                             ->select('to_char(e.date_entree, \'YYYY-MM-DD\') as jour, SUM(e.quantite_litres) as total_litres, f.nom as fournisseur_nom')
                             ->join('fournisseurs f', 'f.id = e.fournisseur_id', 'left')
                             ->where('e.date_entree >=', $dateDebut . ' 00:00:00')
                             ->where('e.date_entree <=', $dateFin . ' 23:59:59')
                             ->groupBy('jour, f.nom');
        $entreesData = $builderEntrees->get()->getResultArray();

        // 3. Requête Sorties (Bocaux)
        $builderSorties = $db->table('sorties s')
                             ->select('to_char(s.date_sortie, \'YYYY-MM-DD\') as jour, SUM(s.quantite) as total_quantite, sm.nom as supermarche_nom')
                             ->join('supermarches sm', 'sm.id = s.supermarche_id', 'left')
                             ->where('s.date_sortie >=', $dateDebut . ' 00:00:00')
                             ->where('s.date_sortie <=', $dateFin . ' 23:59:59')
                             ->groupBy('jour, sm.nom');
        $sortiesData = $builderSorties->get()->getResultArray();

        // 4. Génération de TOUTES les dates de la plage pour l'axe X
        $toutesLesDates = [];
        $courant = strtotime($dateDebut);
        $fin = strtotime($dateFin);
        while ($courant <= $fin) {
            $toutesLesDates[] = date('Y-m-d', $courant);
            $courant = strtotime('+1 day', $courant);
        }

        // 5. Alignement des données sur la plage de dates exacte
        $entreesParDate = [];
        $sortiesParDate = [];

        foreach ($toutesLesDates as $jour) {
            $litresCeJour = 0;
            foreach ($entreesData as $e) {
                if ($e['jour'] === $jour) {
                    $litresCeJour += floatval($e['total_litres']);
                }
            }
            $entreesParDate[] = ['jour' => $jour, 'total_litres' => $litresCeJour];

            $bocauxCeJour = 0;
            foreach ($sortiesData as $s) {
                if ($s['jour'] === $jour) {
                    $bocauxCeJour += intval($s['total_quantite']);
                }
            }
            $sortiesParDate[] = ['jour' => $jour, 'total_quantite' => $bocauxCeJour];
        }

        // 6. Répartition pour les Camemberts
        $camembertFournisseurs = [];
        foreach ($entreesData as $row) {
            $nomNom = $row['fournisseur_nom'] ?: 'Inconnu';
            if (!isset($camembertFournisseurs[$nomNom])) {
                $camembertFournisseurs[$nomNom] = 0;
            }
            $camembertFournisseurs[$nomNom] += floatval($row['total_litres']);
        }
        $entreesParFournisseur = [];
        foreach ($camembertFournisseurs as $nom => $total) {
            $entreesParFournisseur[] = ['fournisseur_nom' => $nom, 'total_litres' => $total];
        }

        $camembertSupermarches = [];
        foreach ($sortiesData as $row) {
            $smNom = $row['supermarche_nom'] ?: 'Inconnu';
            if (!isset($camembertSupermarches[$smNom])) {
                $camembertSupermarches[$smNom] = 0;
            }
            $camembertSupermarches[$smNom] += intval($row['total_quantite']);
        }
        $sortiesParDestinataire = [];
        foreach ($camembertSupermarches as $type => $total) {
            $sortiesParDestinataire[] = ['destinataire_type' => $type, 'total_quantite' => $total];
        }

        // 7. Envoi à la vue
        $data = [
            'titre'                  => 'Tableau de bord - Arovia',
            'solde_disponible'       => $this->statsModel->getSoldeDisponible(),
            'total_entrees_mois'     => $this->statsModel->getTotalEntreesMois(),
            'total_sorties_mois'     => $this->statsModel->getTotalSortiesMois(),
            'benefice_net_mois'      => $this->statsModel->getTotalEntreesMois() - $this->statsModel->getTotalSortiesMois(),
            'donnees_graphique'      => $this->statsModel->getDonneesGraphiqueAnnuel(),
            
            'entreesParDate'         => $entreesParDate,
            'sortiesParDate'         => $sortiesParDate,
            'entreesParFournisseur'  => $entreesParFournisseur,
            'sortiesParDestinataire' => $sortiesParDestinataire,
            'axeDates'               => $toutesLesDates, 
            
            'date_debut'             => $dateDebutRaw,
            'date_fin'               => $dateFinRaw
        ];

        return view('statistiques/tableau_de_bord', $data);
    }
}
