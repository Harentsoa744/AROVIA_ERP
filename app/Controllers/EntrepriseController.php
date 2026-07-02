<?php

namespace App\Controllers;

use App\Models\EntrepriseModel;


class EntrepriseController extends BaseController
{
    protected EntrepriseModel $entrepriseModel;

    public function __construct()
    {
        $this->entrepriseModel = new EntrepriseModel();
    }

    
    private function regles(): array
    {
        return [
            'nom'   => 'required|max_length[150]',
            'email' => 'permit_empty|valid_email',
        ];
    }

    private function messages(): array
    {
        return [
            'nom' => [
                'required'   => 'Le nom est obligatoire.',
                'max_length' => 'Le nom ne doit pas dépasser 150 caractères.',
            ],
            'email' => [
                'valid_email' => "L'adresse email n'est pas valide.",
            ],
        ];
    }

    
    public function index()
    {
        return view('entreprise/index', [
            'titre'       => 'Entreprises',
            'entreprises' => $this->entrepriseModel->orderBy('nom', 'ASC')->findAll(),
        ]);
    }

    
    public function ajout()
    {
        return view('entreprise/ajout', [
            'titre' => 'Ajouter une entreprise',
        ]);
    }

    public function save()
    {
        if (! $this->validate($this->regles(), $this->messages())) {
            return view('entreprise/ajout', [
                'titre'      => 'Ajouter une entreprise',
                'validation' => $this->validator,
                'entreprise' => $this->request->getPost(),
            ]);
        }

        $this->entrepriseModel->insert([
            'nom'       => $this->request->getPost('nom'),
            'telephone' => $this->request->getPost('telephone'),
            'email'     => $this->request->getPost('email'),
        ]);

        return redirect()->to('/entreprise')->with('succes', 'Entreprise ajoutée avec succès.');
    }

    
    public function modifier(int $id)
    {
        $entreprise = $this->entrepriseModel->find($id);

        if (! $entreprise) {
            return redirect()->to('/entreprise')->with('erreur', 'Entreprise introuvable.');
        }

        return view('entreprise/modifier', [
            'titre'      => 'Modifier une entreprise',
            'entreprise' => $entreprise,
        ]);
    }

   
    public function update(int $id)
    {
        if (! $this->validate($this->regles(), $this->messages())) {
            $entreprise       = $this->request->getPost();
            $entreprise['id'] = $id;

            return view('entreprise/modifier', [
                'titre'      => 'Modifier une entreprise',
                'validation' => $this->validator,
                'entreprise' => $entreprise,
            ]);
        }

        $this->entrepriseModel->update($id, [
            'nom'       => $this->request->getPost('nom'),
            'telephone' => $this->request->getPost('telephone'),
            'email'     => $this->request->getPost('email'),
        ]);

        return redirect()->to('/entreprise')->with('succes', 'Entreprise modifiée avec succès.');
    }

    
    public function supprimer(int $id)
    {
        if ($this->entrepriseModel->possedeContrats($id)) {
            return redirect()->to('/entreprise')->with('erreur', 'Impossible de supprimer : des contrats sont liés à cette entreprise.');
        }

        $this->entrepriseModel->delete($id);

        return redirect()->to('/entreprise')->with('succes', 'Entreprise supprimée avec succès.');
    }
}
