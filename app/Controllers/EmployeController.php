<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\PaiementSalaireModel;
use App\Models\PlanningModel;

class EmployeController extends BaseController
{
    protected $employeModel;
    protected $paiementModel;
    protected $planningModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
        $this->paiementModel = new PaiementSalaireModel();
        $this->planningModel = new PlanningModel();
    }

    // LISTE DES EMPLOYÉS
    public function index()
    {
        $data = [
            'employes' => $this->employeModel->findAll()
        ];

        return view('employes/index', $data);
    }

    // AFFICHER UN EMPLOYÉ
    public function show($id)
    {
        $data = [
            'employe' => $this->employeModel->find($id),
            'paiements' => $this->paiementModel->getByEmploye($id),
            'planning' => $this->planningModel->getByEmploye($id)
        ];

        return view('employes/show', $data);
    }

    // FORM CREATE
    public function create()
    {
        return view('employes/create');
    }

    // STORE
    public function store()
    {
        $this->employeModel->save([
            'matricule' => $this->request->getPost('matricule'),
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'adresse' => $this->request->getPost('adresse'),
            'poste' => $this->request->getPost('poste'),
            'salaire_base' => $this->request->getPost('salaire_base'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'statut' => 'ACTIF'
        ]);

        return redirect()->to('/employes');
    }

    // FORM EDIT
    public function edit($id)
    {
        $data = [
            'employe' => $this->employeModel->find($id)
        ];

        return view('employes/edit', $data);
    }

    // UPDATE
    public function update($id)
    {
        $this->employeModel->update($id, [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'poste' => $this->request->getPost('poste'),
            'salaire_base' => $this->request->getPost('salaire_base'),
            'statut' => $this->request->getPost('statut')
        ]);

        return redirect()->to('/employes');
    }

    // DELETE
    public function delete($id)
    {
        $this->employeModel->delete($id);
        return redirect()->to('/employes');
    }
}