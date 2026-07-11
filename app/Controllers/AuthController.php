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

    /**
     * Affiche la page de connexion (GET /)
     */
    public function index()
    {
        // Si l'utilisateur est déjà connecté, on le redirige directement vers l'accueil
        if (session()->get('user_id')) {
            return redirect()->to(base_url('home')); // Remplace par arovia_url('home') si nécessaire
        }

        return view('auth/login');
    }

    /**
     * Traite la tentative de connexion (POST /login)
     */
    public function login()
    {
        // Étape 1 : Si déjà connecté, inutile d'aller plus loin
        if (session()->get('user_id')) {
            return redirect()->to(base_url('home'));
        }

        // Étape 2 : Récupération et nettoyage des données du formulaire
        $email    = trim((string) $this->request->getPost('email'));
        $password = trim((string) $this->request->getPost('password'));

        // Étape 3 : Vérification des identifiants via le modèle
        $user = $this->utilisateurModel->login($email, $password);

        if ($user) {
            // Étape 4 : Injection des données utilisateur dans la session globale
            session()->set([
                'user_id'     => $user['id'],
                'user_nom'    => $user['nom'],
                'user_prenom' => $user['prenom'],
                'user_email'  => $user['email'],
                'user_role'   => $user['role_nom'] ?? 'Utilisateur',
                'user_photo'  => $user['photo_profil'] ?? null,
                'isLoggedIn'  => true
            ]);

            // Redirection réussie
            return redirect()->to(base_url('home'));
        }

        // Étape 5 : En cas d'échec, réaffichage avec message d'erreur
        return view('auth/login', [
            'error' => 'Identifiants invalides ou compte inactif.',
            'email' => $email,
        ]);
    }

    /**
     * Déconnexion de l'utilisateur
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}