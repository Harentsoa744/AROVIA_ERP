<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;

class AuthController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
    }

  
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('home')); 
        }

        return view('auth/login');
    }

  
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('home'));
        }

        $email    = trim((string) $this->request->getPost('email'));
        $password = trim((string) $this->request->getPost('password'));

        $user = $this->utilisateurModel->login($email, $password);

        if ($user) {
            session()->set([
                'user_id'     => $user['id'],
                'user_nom'    => $user['nom'],
                'user_prenom' => $user['prenom'],
                'user_email'  => $user['email'],
                'user_role'   => $user['role_nom'] ?? 'Utilisateur',
                'user_photo'  => $user['photo_profil'] ?? null,
                'isLoggedIn'  => true
            ]);

            return redirect()->to(base_url('home'));
        }

        return view('auth/login', [
            'error' => 'Identifiants invalides ou compte inactif.',
            'email' => $email,
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}