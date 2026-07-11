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
            'employes' => $this->employeModel->getActifs()
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

    public function fire($id)
    {
        $this->employeModel->update($id, ['statut' => 'INACTIF']);
        return redirect()->to('/employes');
    }

    // STORE
    public function store()
    {
        $data = [
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
        ];
        
        // Handle photo upload
        $file = $this->request->getFile('photo_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/employes', $newName);
            
            // Also copy to public directory for web access
            $publicPath = FCPATH . 'uploads/employes/';
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            copy(WRITEPATH . 'uploads/employes/' . $newName, $publicPath . $newName);
            
            $data['photo_profil'] = $newName;
        }
        
        $this->employeModel->save($data);

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
        $data = [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
            'email' => $this->request->getPost('email'),
            'poste' => $this->request->getPost('poste'),
            'salaire_base' => $this->request->getPost('salaire_base'),
            'statut' => $this->request->getPost('statut')
        ];
        
        // Handle photo upload
        $file = $this->request->getFile('photo_profil');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/employes', $newName);
            
            // Also copy to public directory for web access
            $publicPath = FCPATH . 'uploads/employes/';
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            copy(WRITEPATH . 'uploads/employes/' . $newName, $publicPath . $newName);
            
            $data['photo_profil'] = $newName;
        }
        
        $this->employeModel->update($id, $data);

        return redirect()->to('/employes');
    }

    // DELETE
    public function delete($id)
    {
        $this->employeModel->delete($id);
        return redirect()->to('/employes');
    }


    // UPLOAD PHOTO DE PROFIL
    public function uploadPhoto($id)
    {
        $file = $this->request->getFile('photo_profil');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate unique filename
            $newName = $file->getRandomName();
            
            // Move file to uploads directory
            $file->move(WRITEPATH . 'uploads/employes', $newName);
            
            // Also copy to public directory for web access
            $publicPath = FCPATH . 'uploads/employes/';
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            copy(WRITEPATH . 'uploads/employes/' . $newName, $publicPath . $newName);
            
            // Update database
            $this->employeModel->update($id, ['photo_profil' => $newName]);
            
            return redirect()->to('/employes/edit/' . $id)->with('success', 'Photo de profil mise à jour avec succès.');
        }
        
        return redirect()->to('/employes/edit/' . $id)->with('error', 'Erreur lors du téléchargement de la photo.');
    }

    public function ajaxList()
{
    $request = service('request');

    $search = $request->getGet('search');
    $statut = $request->getGet('statut');

    $builder = $this->employeModel;

    if (!empty($statut)) {
        $builder = $builder->where('statut', $statut);
    }

    if (!empty($search)) {
        $builder = $builder->groupStart()
            ->like('nom', $search)
            ->orLike('prenom', $search)
            ->orLike('matricule', $search)
            ->orLike('poste', $search)
            // ->orLike('salaire_base', $search)
            ->orLike('telephone', $search)
            ->groupEnd();
    }

    $employes = $builder->findAll();

    return $this->response->setJSON($employes);
}
}