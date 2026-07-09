<?php

namespace App\Controllers;

use App\Models\SupermarcheModel;

class Supermarches extends BaseController
{
    protected SupermarcheModel $supermarcheModel;

    public function __construct()
    {
        $this->supermarcheModel = new SupermarcheModel();
    }

    public function index()
    {
        $data['supermarches'] = $this->supermarcheModel->findAll();
        return view('supermarches/index', $data);
    }

    public function new()
    {
        return view('supermarches/new');
    }

    public function create()
    {
        $data = [
            'nom'          => $this->request->getPost('nom'),
            'contact'      => $this->request->getPost('contact'),
            'localisation' => $this->request->getPost('localisation'),
        ];

        if (! $this->supermarcheModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->supermarcheModel->errors());
        }

        return redirect()->to('/supermarches')->with('message', 'Supermarché ajouté avec succès.');
    }

    public function edit($id)
    {
        $data['supermarche'] = $this->supermarcheModel->find($id);

        if (! $data['supermarche']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('supermarches/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'nom'          => $this->request->getPost('nom'),
            'contact'      => $this->request->getPost('contact'),
            'localisation' => $this->request->getPost('localisation'),
        ];

        if (! $this->supermarcheModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->supermarcheModel->errors());
        }

        return redirect()->to('/supermarches')->with('message', 'Supermarché modifié avec succès.');
    }

    public function delete($id)
    {
        $this->supermarcheModel->delete($id);
        return redirect()->to('/supermarches')->with('message', 'Supermarché supprimé.');
    }
}
