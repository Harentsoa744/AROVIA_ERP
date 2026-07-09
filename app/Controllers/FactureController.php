<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TypeBocalModel;
use App\Models\VenteDetailModel;
use App\Models\VenteModel;

/**
 * Contrôleur de gestion des factures de vente
 * Projet Arovia — ERP Miel
 */
class FactureController extends BaseController
{
    protected VenteModel $venteModel;
    protected VenteDetailModel $venteDetailModel;
    protected ClientModel $clientModel;
    protected TypeBocalModel $typeBocalModel;

    public function __construct()
    {
        $this->venteModel       = new VenteModel();
        $this->venteDetailModel = new VenteDetailModel();
        $this->clientModel      = new ClientModel();
        $this->typeBocalModel   = new TypeBocalModel();
    }

    /**
     * GET /factures
     * Liste de toutes les factures.
     */
    public function index()
    {
        $data = [
            'titre'  => 'Factures de vente — Arovia',
            'ventes' => $this->venteModel->getListeVentes(),
        ];

        return view('facture/liste', $data);
    }

    /**
     * GET /factures/creer
     * Formulaire de création d'une nouvelle facture.
     */
    public function creer()
    {
        $data = [
            'titre'   => 'Nouvelle facture — Arovia',
            'clients' => $this->clientModel->orderBy('nom', 'ASC')->findAll(),
            'bocaux'  => $this->typeBocalModel->findAll(),
        ];

        return view('facture/creer', $data);
    }

    /**
     * POST /factures/enregistrer
     * Valide et enregistre l'entête + les lignes de la facture, dans une transaction.
     */
    public function enregistrer()
    {
        $rules = [
            'client_id'       => 'required|integer',
            'type_bocal_id.*' => 'permit_empty',
            'quantite.*'      => 'permit_empty',
            'prix_unitaire.*' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/factures/creer')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $typeBocalIds  = $this->request->getPost('type_bocal_id') ?? [];
        $quantites     = $this->request->getPost('quantite') ?? [];
        $prixUnitaires = $this->request->getPost('prix_unitaire') ?? [];

        // Construction et validation des lignes envoyées par le formulaire
        $lignes       = [];
        $montantTotal = 0;

        foreach ($typeBocalIds as $index => $typeBocalId) {
            $quantite     = (int) ($quantites[$index] ?? 0);
            $prixUnitaire = (float) ($prixUnitaires[$index] ?? 0);

            if (empty($typeBocalId) || $quantite <= 0) {
                continue; // ligne vide ignorée
            }

            $totalLigne    = $quantite * $prixUnitaire;
            $montantTotal += $totalLigne;

            $lignes[] = [
                'type_bocal_id' => (int) $typeBocalId,
                'quantite'      => $quantite,
                'prix_unitaire' => $prixUnitaire,
                'total_ligne'   => $totalLigne,
            ];
        }

        if (empty($lignes)) {
            return redirect()->to('/factures/creer')
                ->withInput()
                ->with('error', 'Veuillez ajouter au moins un article valide à la facture.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $venteId = $this->venteModel->insert([
            'client_id'     => $this->request->getPost('client_id'),
            'date_vente'    => date('Y-m-d H:i:s'),
            'montant_total' => $montantTotal,
            'mode_paiement' => $this->request->getPost('mode_paiement') ?: 'Cash',
            'statut'        => 'EN_COURS',
        ]);

        foreach ($lignes as $ligne) {
            $ligne['vente_id'] = $venteId;
            $this->venteDetailModel->insert($ligne);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/factures/creer')
                ->withInput()
                ->with('error', "Une erreur est survenue lors de l'enregistrement de la facture.");
        }

        return redirect()->to('/factures/' . $venteId)
            ->with('success', 'Facture créée avec succès.');
    }

    /**
     * GET /factures/(:num)
     * Affiche la facture au format imprimable Arovia.
     */
    public function afficher(int $id)
    {
        $vente = $this->venteModel->getVenteAvecClient($id);

        if (! $vente) {
            return redirect()->to('/factures')->with('error', 'Facture introuvable.');
        }

        $data = [
            'titre'  => 'Facture N°' . str_pad((string) $vente['id'], 5, '0', STR_PAD_LEFT),
            'vente'  => $vente,
            'lignes' => $this->venteDetailModel->getLignesFacture($id),
        ];

        return view('facture/facture', $data);
    }

    /**
     * POST /factures/(:num)/statut
     * Marque la facture comme payée ou en cours.
     */
    public function changerStatut(int $id)
    {
        $statut = $this->request->getPost('statut');

        if (! in_array($statut, ['PAYE', 'EN_COURS'], true)) {
            return redirect()->back()->with('error', 'Statut invalide.');
        }

        $this->venteModel->update($id, ['statut' => $statut]);

        return redirect()->to('/factures/' . $id)->with('success', 'Statut mis à jour.');
    }

    /**
     * GET /factures/(:num)/supprimer
     * Supprime la facture ainsi que ses lignes.
     */
    public function supprimer(int $id)
    {
        $this->venteDetailModel->supprimerLignesDeVente($id);
        $this->venteModel->delete($id);

        return redirect()->to('/factures')->with('success', 'Facture supprimée.');
    }
}
