<?php

namespace App\Models;

use CodeIgniter\Model;

class StockProduitFiniModel extends Model
{
    protected $table         = 'stock_produit_fini';
    protected $primaryKey    = 'type_bocal_id';
    protected $allowedFields = ['quantite_disponible', 'seuil_alerte'];

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    public function getStockAvecTypes()
    {
        return $this->select('stock_produit_fini.*, types_bocaux.nom, types_bocaux.volume_litres, types_bocaux.cible, types_bocaux.prix_vente')
                    ->join('types_bocaux', 'types_bocaux.id = stock_produit_fini.type_bocal_id')
                    ->findAll();
    }
}