<?php

namespace App\Controllers;

use App\Models\LivreurModel;

class LivreurController extends BaseController
{
    protected $livreurModel;

    public function __construct()
    {
        $this->livreurModel = new LivreurModel();
    }

    public function index()
    {
        $data = [
            'livreurs' => $this->livreurModel->findAll()
        ];
        return view('livreurs/index', $data);
    }

    public function store()
    {
        $this->livreurModel->save([
            'nom'       => $this->request->getPost('nom'),
            'telephone' => $this->request->getPost('telephone'),
            'vehicule'  => $this->request->getPost('vehicule'),
            'disponible'=> true
        ]);
        return redirect()->to('/livreurs');
    }

    public function edit($id)
    {
        $data = [
            'livreur' => $this->livreurModel->find($id)
        ];
        return view('livreurs/edit', $data);
    }

    public function update($id)
    {
        $this->livreurModel->update($id, [
            'nom'       => $this->request->getPost('nom'),
            'telephone' => $this->request->getPost('telephone'),
            'vehicule'  => $this->request->getPost('vehicule'),
            'disponible'=> $this->request->getPost('disponible') ? true : false
        ]);
        return redirect()->to('/livreurs');
    }
}