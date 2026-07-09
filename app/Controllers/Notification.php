<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Notification extends BaseController
{
    private NotificationModel $notificationModel;


    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }


    /**
     * Retourne la liste des notifications de l'utilisateur connecté
     * AJAX GET /notification/liste
     */
    public function liste()
    {
        $user_id = session()->get('user_id');


        if (!$user_id) {
            return $this->response
                        ->setStatusCode(401)
                        ->setJSON([
                            'error' => 'Utilisateur non connecté'
                        ]);
        }


        $notifications = $this->notificationModel
                              ->getNotifications($user_id);


        return $this->response
                    ->setJSON($notifications);
    }




    /**
     * Retourne le nombre de notifications non lues
     * AJAX GET /notification/count
     */
    public function count()
    {
        $user_id = session()->get('user_id');


        if (!$user_id) {
            return $this->response
                        ->setStatusCode(401)
                        ->setJSON([
                            'count' => 0
                        ]);
        }


        $count = $this->notificationModel
                      ->countNonLues($user_id);


        return $this->response
                    ->setJSON([
                        'count' => $count
                    ]);
    }




    /**
     * Marquer une notification comme lue
     * AJAX POST /notification/lire/{id}
     */
    public function lire($id)
    {

        $user_id = session()->get('user_id');


        if (!$user_id) {
            return $this->response
                        ->setStatusCode(401)
                        ->setJSON([
                            'success'=>false
                        ]);
        }



        // Vérifie que la notification appartient bien à l'utilisateur
        $notification = $this->notificationModel
                             ->where([
                                'id'=>$id,
                                'utilisateur_id'=>$user_id
                             ])
                             ->first();



        if (!$notification) {

            return $this->response
                        ->setStatusCode(404)
                        ->setJSON([
                            'success'=>false,
                            'message'=>'Notification introuvable'
                        ]);
        }



        $this->notificationModel->lire($id);



        return $this->response
                    ->setJSON([
                        'success'=>true
                    ]);

    }





    /**
     * Créer une notification
     * POST /notification/create
     */
    public function create()
    {

        $data = [

            'utilisateur_id' => 
                $this->request->getPost('utilisateur_id'),


            'titre' =>
                $this->request->getPost('titre'),


            'message' =>
                $this->request->getPost('message'),


            'type' =>
                $this->request->getPost('type') ?? 'INFO',


            'lien' =>
                $this->request->getPost('lien'),


            'lu'=>false
        ];



        $this->notificationModel
             ->ajouter($data);



        return redirect()->back();

    }




    /**
     * Création rapide depuis le code
     * Exemple :
     * $this->notification->envoyer(...)
     */
    public function envoyer(
        $utilisateur_id,
        $titre,
        $message,
        $type='INFO',
        $lien=null
    )
    {

        return $this->notificationModel->ajouter([

            'utilisateur_id'=>$utilisateur_id,

            'titre'=>$titre,

            'message'=>$message,

            'type'=>$type,

            'lien'=>$lien,

            'lu'=>false

        ]);

    }

}