<?php

namespace App\Controllers;

use App\Models\LivreurModel;
use App\Models\LivraisonModel;

class LivraisonController extends BaseController
{
    protected $livreurModel;
    protected $livraisonModel;

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
        $this->livreurModel = new LivreurModel();
        $this->livraisonModel = new LivraisonModel();
    }

    public function index()
    {
        $data = [
            'livraisons_en_cours' => $this->livraisonModel->getLivraisonsWithLivreur(['EN_COURS', 'EN_ATTENTE']),
            'livraisons_faites'   => $this->livraisonModel->getLivraisonsWithLivreur(['EFFECTUEE']),
            'livraisons'         => $this->livraisonModel->getLivraisonsWithLivreur(), // All livraisons
            'stats'               => $this->livraisonModel->getStats(),
            'livreurs_dispo'      => $this->livreurModel->getDisponibles(),
            'ventes'              => $this->getVentes(),
            'clients'             => $this->getClients(),
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

    public function create()
    {
        $data = [
            'livreurs_dispo' => $this->livreurModel->getDisponibles(),
            'ventes'         => $this->getVentes(),
            'clients'        => $this->getClients(),
        ];
        return view('livraisons/create', $data);
    }

    public function store()
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

        $this->livraisonModel->save([
            'vente_id'          => $this->request->getPost('vente_id'),
            'livreur_id'        => $this->request->getPost('livreur_id'),
            'date_prevue'       => $this->normalizeDateTime($this->request->getPost('date_prevue')),
            'adresse_livraison' => $adresseLivraison,
            'statut'            => 'EN_ATTENTE',
        ]);

        return redirect()->to('/livraisons');
    }

    public function updateStatut($id, $statut)
    {
        $updateData = ['statut' => strtoupper($statut)];
        if (strtoupper($statut) === 'EFFECTUEE') {
            $updateData['date_effective'] = date('Y-m-d H:i:s');
        }

        $this->livraisonModel->update($id, $updateData);
        return redirect()->to('/livraisons');
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