<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filtre de contrôle d'accès par rôle
 * Vérifie que l'utilisateur a le rôle requis pour accéder à la page
 */
class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('/'));
        }

        $userRole = session()->get('user_role');
        
        // Si aucun argument n'est passé, on autorise tous les rôles connectés
        if (empty($arguments)) {
            return;
        }

        // Vérifier si le rôle de l'utilisateur est dans la liste des rôles autorisés
        // Comparaison insensible à la casse
        $userRoleLower = strtolower($userRole ?? '');
        $allowedRoles = array_map('strtolower', $arguments);
        
        if (! in_array($userRoleLower, $allowedRoles, true)) {
            // Rediriger vers la page d'accueil avec un message d'erreur
            return redirect()->to(base_url('home'))->with('error', 'Accès non autorisé pour votre rôle.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête
    }
}
