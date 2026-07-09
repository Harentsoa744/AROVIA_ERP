<?php

namespace App\Controllers;

use App\Models\LivraisonModel;
use App\Models\LivreurModel;

class PlanningController extends BaseController
{
    protected $livraisonModel;
    protected $livreurModel;

    private function normalizeDateTime(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $timestamp = strtotime($value);

        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }

    private function getVentes(): array
    {
        $db = \Config\Database::connect();

        return $db->table('ventes')
            ->select('ventes.id, ventes.montant_total, ventes.date_vente, clients.nom as client_nom')
            ->join('clients', 'clients.id = ventes.client_id', 'left')
            ->orderBy('ventes.id', 'DESC')
            ->get()
            ->getResultArray();
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
        $this->livraisonModel = new LivraisonModel();
        $this->livreurModel   = new LivreurModel();
    }

    /**
     * Page calendrier (FullCalendar)
     */
    public function index()
    {
        return view('planning/calendrier');
    }

    /**
     * API JSON — retourne les livraisons au format FullCalendar
     */
    public function events()
    {
        // Récupérer toutes les livraisons avec le nom du livreur
        $livraisons = $this->livraisonModel
            ->select('livraisons.*, livreurs.nom as livreur_nom')
            ->join('livreurs', 'livreurs.id = livraisons.livreur_id', 'left')
            ->findAll();

        // Transformer en format FullCalendar
        $events = [];
        foreach ($livraisons as $liv) {
            $events[] = [
                'id'    => $liv['id'],
                'title' => ($liv['livreur_nom'] ?? 'Non assigné') . ' → ' . ($liv['adresse_livraison'] ?? ''),
                'start' => $liv['date_prevue'],
                'end'   => $liv['date_effective'] ?? null,
                'statut' => $liv['statut'],
            ];
        }

        return $this->response->setJSON($events);
    }

    /**
     * Liste des livraisons (tableau)
     */
    public function liste()
    {
        $livraisons = $this->livraisonModel
            ->select('livraisons.*, livreurs.nom as nom_livreur')
            ->join('livreurs', 'livreurs.id = livraisons.livreur_id', 'left')
            ->orderBy('date_prevue', 'DESC')
            ->findAll();

        return view('planning/index', ['livraisons' => $livraisons]);
    }

    /**
     * Formulaire d'ajout
     */
    public function ajouter()
    {
        $ventes = $this->getVentes();
        $clients = $this->getClients();
        $livreurs = $this->livreurModel->findAll();

        return view('planning/ajouter', [
            'ventes'   => $ventes,
            'clients'  => $clients,
            'livreurs' => $livreurs,
        ]);
    }

    /**
     * Enregistrer une nouvelle livraison
     */
    public function save()
    {
        $clientId = $this->request->getPost('client_id');
        $adresseLivraison = trim((string) $this->request->getPost('adresse_livraison'));

        if (!empty($clientId) && is_numeric($clientId)) {
            $db = \Config\Database::connect();
            $client = $db->table('clients')
                ->select('adresse')
                ->where('id', (int) $clientId)
                ->get()
                ->getRowArray();

            if (!empty($client['adresse'])) {
                $adresseLivraison = trim((string) $client['adresse']);
            }
        }

        $venteId = $this->request->getPost('vente_id');
        $venteId = (!empty($venteId) && is_numeric($venteId)) ? (int) $venteId : null;

        $livreurId = $this->request->getPost('livreur_id');
        $livreurId = (!empty($livreurId) && is_numeric($livreurId)) ? (int) $livreurId : null;

        $datePrevue = $this->request->getPost('date_prevue');
        $datePrevue = $this->normalizeDateTime($datePrevue);

        $this->livraisonModel->save([
            'vente_id'          => $venteId,
            'livreur_id'        => $livreurId,
            'date_prevue'       => $datePrevue,
            'adresse_livraison' => $adresseLivraison,
            'statut'            => $this->request->getPost('statut') ?? 'EN_ATTENTE',
        ]);

        return redirect()->to('/planning/liste');
    }

    /**
     * Détails d'une livraison
     */
    public function details($id)
    {
        $livraison = $this->livraisonModel
            ->select('livraisons.*, livreurs.nom as nom_livreur, livreurs.telephone, livreurs.vehicule, ventes.montant_total, clients.nom as client_nom')
            ->join('livreurs', 'livreurs.id = livraisons.livreur_id', 'left')
            ->join('ventes', 'ventes.id = livraisons.vente_id', 'left')
            ->join('clients', 'clients.id = ventes.client_id', 'left')
            ->where('livraisons.id', $id)
            ->first();

        return view('planning/details', ['livraison' => $livraison]);
    }

    /**
     * Formulaire de modification
     */
    public function modifier($id)
    {
        $livraison = $this->livraisonModel->find($id);
        $ventes = $this->getVentes();
        $clients = $this->getClients();
        $livreurs  = $this->livreurModel->findAll();

        return view('planning/modifier', [
            'livraison' => $livraison,
            'ventes'    => $ventes,
            'clients'   => $clients,
            'livreurs'  => $livreurs,
        ]);
    }

    /**
     * Mettre à jour une livraison
     */
    public function update($id)
    {
        $clientId = $this->request->getPost('client_id');
        $adresseLivraison = trim((string) $this->request->getPost('adresse_livraison'));

        if (!empty($clientId) && is_numeric($clientId)) {
            $db = \Config\Database::connect();
            $client = $db->table('clients')
                ->select('adresse')
                ->where('id', (int) $clientId)
                ->get()
                ->getRowArray();

            if (!empty($client['adresse'])) {
                $adresseLivraison = trim((string) $client['adresse']);
            }
        }

        $venteId = $this->request->getPost('vente_id');
        $venteId = (!empty($venteId) && is_numeric($venteId)) ? (int) $venteId : null;

        $livreurId = $this->request->getPost('livreur_id');
        $livreurId = (!empty($livreurId) && is_numeric($livreurId)) ? (int) $livreurId : null;

        $datePrevue = $this->request->getPost('date_prevue');
        $datePrevue = $this->normalizeDateTime($datePrevue);

        $dateEffective = $this->request->getPost('date_effective');
        $dateEffective = $this->normalizeDateTime($dateEffective);

        $data = [
            'vente_id'          => $venteId,
            'livreur_id'        => $livreurId,
            'date_prevue'       => $datePrevue,
            'date_effective'    => $dateEffective,
            'adresse_livraison' => $adresseLivraison,
            'statut'            => $this->request->getPost('statut'),
        ];

        $this->livraisonModel->update($id, $data);

        return redirect()->to('/planning/liste');
    }

    /**
     * Supprimer une livraison
     */
    public function delete($id)
    {
        $this->livraisonModel->delete($id);

        return redirect()->to('/planning/liste');
    }
}
