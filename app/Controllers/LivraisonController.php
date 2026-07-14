<?php

namespace App\Controllers;

use App\Models\LivreurModel;
use App\Models\LivraisonModel;
use App\Models\HistoriqueLivraisonModel;

class LivraisonController extends BaseController
{
    protected $livreurModel;
    protected $livraisonModel;
    protected $historiqueLivraisonModel;

    private function normalizeDateTime(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $timestamp = strtotime($value);

        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    private function getClients(): array
    {
        $db = \Config\Database::connect();

        return $db->table('clients')
            ->select('id, nom, type_client, telephone, email, adresse')
            ->orderBy('nom', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function __construct()
    {
        $this->livreurModel = new LivreurModel();
        $this->livraisonModel = new LivraisonModel();
        $this->historiqueLivraisonModel = new HistoriqueLivraisonModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // Récupérer les sorties à livrer (statut A_LIVRER et livraison_ou_point_vente LIVRAISON)
        $sortiesALivrer = $db->table('sorties')
            ->select('sorties.*, supermarches.nom as supermarche_nom, supermarches.localisation as supermarche_adresse')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left')
            ->where('sorties.livraison_ou_point_vente', 'LIVRAISON')
            ->where('sorties.statut', 'A_LIVRER')
            ->orderBy('sorties.date_sortie', 'DESC')
            ->get()
            ->getResultArray();

        // Filtrer les sorties pour aujourd'hui
        $sortiesAujourdhui = array_values(array_filter($sortiesALivrer, function($sortie) use ($today) {
            return date('Y-m-d', strtotime($sortie['date_sortie'])) === $today;
        }));

        // Récupérer les livraisons existantes
        $livraisons = $this->livraisonModel->getLivraisonsWithLivreur();
        
        // Filtrer les livraisons pour aujourd'hui
        $livraisonsAujourdhui = array_values(array_filter($livraisons, function($livraison) use ($today) {
            return date('Y-m-d', strtotime($livraison['date_prevue'])) === $today;
        }));

        $data = [
            'sorties_a_livrer' => $sortiesALivrer,
            'sorties_aujourdhui' => $sortiesAujourdhui,
            'livraisons' => $livraisons,
            'livraisons_aujourdhui' => $livraisonsAujourdhui,
            'livraisons_en_cours' => $this->livraisonModel->getLivraisonsWithLivreur(['EN_COURS', 'EN_ATTENTE']),
            'livraisons_faites' => $this->livraisonModel->getLivraisonsWithLivreur(['EFFECTUEE']),
            'stats' => $this->livraisonModel->getStats(),
            'livreurs_dispo' => $this->livreurModel->getDisponibles(),
            'clients' => $this->getClients(),
        ];

        return view('livraisons/index', $data);
    }

    public function historique()
    {
        $data = [
            'livraisons' => $this->livraisonModel->getLivraisonsWithLivreur()
        ];
        return view('livraisons/historique', $data);
    }

    public function updateStatut($id, $statut)
    {
        // Récupérer la livraison actuelle
        $livraison = $this->livraisonModel->find($id);
        
        if (!$livraison) {
            return redirect()->to('/livraisons')->with('error', 'Livraison introuvable.');
        }

        $statutActuel = $livraison['statut'];
        $nouveauStatut = strtoupper($statut);

        // Validation des transitions de statut
        $transitionsValides = [
            'EN_ATTENTE' => ['EN_COURS', 'ANNULEE'],
            'EN_COURS' => ['EFFECTUEE', 'EN_RETARD', 'ANNULEE'],
            'EFFECTUEE' => [], // Statut final, ne peut plus changer
            'EN_RETARD' => ['EFFECTUEE', 'ANNULEE'],
            'ANNULEE' => [], // Statut final, ne peut plus changer
        ];

        if (!in_array($nouveauStatut, $transitionsValides[$statutActuel] ?? [])) {
            return redirect()->to('/livraisons')->with('error', 'Transition de statut non autorisée de ' . $statutActuel . ' vers ' . $nouveauStatut);
        }

        $updateData = ['statut' => $nouveauStatut];
        if ($nouveauStatut === 'EFFECTUEE' || $nouveauStatut === 'EN_RETARD') {
            $updateData['date_effective'] = date('Y-m-d H:i:s');
        }

        $this->livraisonModel->update($id, $updateData);

        // Enregistrer dans l'historique
        $utilisateurId = session()->get('user_id') ?? null;
        $this->historiqueLivraisonModel->enregistrerChangement(
            $id,
            $statutActuel,
            $nouveauStatut,
            $utilisateurId
        );

        return redirect()->to('/livraisons')->with('message', 'Statut mis à jour avec succès.');
    }

    public function assigner($sortieId)
    {
        $db = \Config\Database::connect();
        
        // Récupérer la sortie
        $sortie = $db->table('sorties')
            ->select('sorties.*, supermarches.nom as supermarche_nom, supermarches.localisation as supermarche_adresse')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left')
            ->where('sorties.id', $sortieId)
            ->first();

        if (!$sortie) {
            return redirect()->to('/livraisons')->with('error', 'Sortie introuvable.');
        }

        $data = [
            'sortie' => $sortie,
            'livreurs_dispo' => $this->livreurModel->getDisponibles(),
        ];

        return view('livraisons/assigner', $data);
    }

    public function storeAssignation()
    {
        $rules = [
            'sortie_id' => 'required|is_natural_no_zero',
            'livreur_id' => 'required|is_natural_no_zero',
            'date_prevue' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sortieId = $this->request->getPost('sortie_id');
        $livreurId = $this->request->getPost('livreur_id');
        $datePrevue = $this->normalizeDateTime($this->request->getPost('date_prevue'));

        $db = \Config\Database::connect();
        
        // Récupérer la sortie pour obtenir l'adresse
        $sortie = $db->table('sorties')
            ->select('sorties.*, supermarches.localisation as supermarche_adresse')
            ->join('supermarches', 'supermarches.id = sorties.supermarche_id', 'left')
            ->where('sorties.id', $sortieId)
            ->first();

        if (!$sortie) {
            return redirect()->to('/livraisons')->with('error', 'Sortie introuvable.');
        }

        // Créer la livraison
        $this->livraisonModel->save([
            'sortie_id' => $sortieId,
            'livreur_id' => $livreurId,
            'date_prevue' => $datePrevue,
            'adresse_livraison' => $sortie['supermarche_adresse'] ?? '',
            'statut' => 'EN_ATTENTE',
        ]);

        // Enregistrer dans l'historique (création)
        $livraisonId = $this->livraisonModel->getInsertID();
        $utilisateurId = session()->get('user_id') ?? null;
        $this->historiqueLivraisonModel->enregistrerChangement(
            $livraisonId,
            null,
            'EN_ATTENTE',
            $utilisateurId,
            'Livraison créée à partir de la sortie #' . $sortieId
        );

        // Mettre à jour le statut de la sortie
        $db->table('sorties')
            ->where('id', $sortieId)
            ->update(['statut' => 'A_LIVRER']);

        return redirect()->to('/livraisons')->with('message', 'Livraison assignée avec succès.');
    }

    public function statutSortie($sortieId, $statut)
    {
        $db = \Config\Database::connect();
        
        $db->table('sorties')
            ->where('id', $sortieId)
            ->update(['statut' => $statut]);

        return redirect()->to('/livraisons')->with('message', 'Statut mis à jour.');
    }

    public function ajaxList()
    {
        $request = service('request');
        $search  = $request->getGet('search');
        $statut  = $request->getGet('statut');

        $builder = $this->livraisonModel->select('livraisons.*, livreurs.nom as livreur_nom')
                                        ->join('livreurs', 'livreurs.id = livraisons.livreur_id', 'left');

        if (!empty($statut)) {
            $builder->where('livraisons.statut', $statut);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('adresse_livraison', $search)
                    ->orLike('livreurs.nom', $search)
                    ->groupEnd();
        }

        return $this->response->setJSON($builder->findAll());
    }
}