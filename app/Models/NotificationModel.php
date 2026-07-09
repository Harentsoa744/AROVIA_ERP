<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{

    protected $table = 'notifications';

    protected $primaryKey = 'id';


    protected $allowedFields = [
        'utilisateur_id',
        'titre',
        'message',
        'type',
        'lien',
        'lu'
    ];


    protected $useTimestamps = false;



    /**
     * Liste notifications utilisateur
     */
    public function getNotifications($utilisateur_id)
    {

        return $this->where('utilisateur_id', $utilisateur_id)
                    ->orderBy('date_creation','DESC')
                    ->findAll();

    }




    /**
     * Nombre non lues
     */
    public function countNonLues($utilisateur_id)
    {

        return $this->where([
                'utilisateur_id'=>$utilisateur_id,
                'lu'=>false
            ])
            ->countAllResults();

    }





    /**
     * Marquer une notification comme lue
     */
    public function lire($id)
    {

        return $this->update($id,[

            'lu'=>true

        ]);

    }





    /**
     * Ajouter notification
     */
    public function ajouter($data)
    {

        return $this->insert($data);

    }



}