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
     * Modifié pour utiliser les sorties existantes
     */
    public function creer()
    {
        $sortieModel = new \App\Models\SortieModel();
        
        $data = [
            'titre'    => 'Nouvelle facture — Arovia',
            'clients'  => $this->clientModel->orderBy('nom', 'ASC')->findAll(),
            'sorties'  => $sortieModel->getSortiesNonFacturees(),
            'bocaux'   => $this->typeBocalModel->findAll(),
        ];

        return view('facture/creer', $data);
    }

    /**
     * POST /factures/enregistrer
     * Valide et enregistre l'entête + les lignes de la facture, dans une transaction.
     * Version modifiée pour utiliser les sorties existantes
     */
    public function enregistrer()
    {
        $rules = [
            'sortie_id'      => 'required|integer',
            'client_id'      => 'required|integer',
            'mode_paiement'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/factures/creer')
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $sortieId = (int) $this->request->getPost('sortie_id');
        $clientId = (int) $this->request->getPost('client_id');
        $modePaiement = $this->request->getPost('mode_paiement');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Créer la vente à partir de la sortie
            $venteId = $this->venteModel->creerDepuisSortie($sortieId, $clientId, $modePaiement);
            
            // Créer les détails de vente
            $this->venteDetailModel->creerDepuisSortie($venteId, $sortieId);
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->to('/factures/creer')
                    ->withInput()
                    ->with('error', "Une erreur est survenue lors de l'enregistrement de la facture.");
            }

            return redirect()->to('/factures/' . $venteId)
                ->with('success', 'Facture créée avec succès à partir de la sortie.');
                
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/factures/creer')
                ->withInput()
                ->with('error', "Erreur: " . $e->getMessage());
        }
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
