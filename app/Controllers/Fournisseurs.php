<?php

namespace App\Controllers;

use App\Models\FournisseurModel;

class Fournisseurs extends BaseController
{
    protected FournisseurModel $fournisseurModel;

    public function __construct()
    {
        $this->fournisseurModel = new FournisseurModel();
    }

    public function index()
    {
        $data['fournisseurs'] = $this->fournisseurModel->findAll();
        return view('fournisseurs/index', $data);
    }

    public function new()
    {
        return view('fournisseurs/new');
    }

    public function create()
    {
        $data = [
            'nom'           => $this->request->getPost('nom'),
            'contact'       => $this->request->getPost('contact'),
            'localisation'  => $this->request->getPost('localisation'),
        ];

        if (! $this->fournisseurModel->save($data)) {
            // la validation a échoué, on retourne au formulaire avec les erreurs
            return redirect()->back()->withInput()->with('errors', $this->fournisseurModel->errors());
        }

        return redirect()->to('/fournisseurs')->with('message', 'Fournisseur ajouté avec succès.');
    }

    public function edit($id)
    {
        $data['fournisseur'] = $this->fournisseurModel->find($id);

        if (! $data['fournisseur']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('fournisseurs/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'nom'           => $this->request->getPost('nom'),
            'contact'       => $this->request->getPost('contact'),
            'localisation'  => $this->request->getPost('localisation'),
        ];

        if (! $this->fournisseurModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->fournisseurModel->errors());
        }

        return redirect()->to('/fournisseurs')->with('message', 'Fournisseur modifié avec succès.');
    }

    public function delete($id)
    {
        $this->fournisseurModel->delete($id);
        return redirect()->to('/fournisseurs')->with('message', 'Fournisseur supprimé.');
    }
}