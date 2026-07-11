<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;

class ProfilController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;
    protected $session;

    /**
     * En CI4, on utilise initController plutôt que __construct 
     * pour éviter de casser la session et le cycle de requêtes.
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->utilisateurModel = new UtilisateurModel();
        $this->session          = session();
    }

    public function index()
    {
        $userId = (int) $this->session->get('user_id');
        $user   = $this->utilisateurModel->findUserById($userId);

        if (! $user) {
            $this->session->destroy();
            return redirect()->to(base_url('/'));
        }

        return view('profil/index', [
            'user'    => $user,
            'success' => $this->session->getFlashdata('success'),
            'error'   => $this->session->getFlashdata('error'),
        ]);
    }

    public function update()
    {
        // En CI4 moderne, on préfère utiliser $this->request->is('post')
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('profil'));
        }

        $userId   = (int) $this->session->get('user_id');
        $nom      = trim((string) $this->request->getPost('nom'));
        $prenom   = trim((string) $this->request->getPost('prenom'));
        $email    = trim((string) $this->request->getPost('email'));
        $password = trim((string) $this->request->getPost('password'));

        try {
            $data = [
                'nom'    => $nom,
                'prenom' => $prenom,
                'email'  => $email,
            ];

            if ($password !== '') {
                // Utilisation du mécanisme par défaut PASSWORD_DEFAULT conforme PHP 8+
                $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            // Handle photo upload
            $file = $this->request->getFile('photo_profil');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads/utilisateurs', $newName);
                
                // Also copy to public directory for web access
                $publicPath = FCPATH . 'uploads/utilisateurs/';
                if (!is_dir($publicPath)) {
                    mkdir($publicPath, 0755, true);
                }
                copy(WRITEPATH . 'uploads/utilisateurs/' . $newName, $publicPath . $newName);
                
                $data['photo_profil'] = $newName;
            }

            $this->utilisateurModel->update($userId, $data);

            // Mise à jour immédiate de la session pour l'affichage dynamique du header
            $sessionData = [
                'user_nom'    => $nom,
                'user_prenom' => $prenom,
                'user_email'  => $email,
            ];
            
            if (isset($data['photo_profil'])) {
                $sessionData['user_photo'] = $data['photo_profil'];
            }
            
            $this->session->set($sessionData);

            $this->session->setFlashdata('success', 'Profil mis à jour avec succès.');
        } catch (\Throwable $e) {
            $this->session->setFlashdata('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }

        return redirect()->to(base_url('profil'));
    }
}