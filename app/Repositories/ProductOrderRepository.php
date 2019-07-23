<?php

namespace App\Repositories;

use App\Models\ProductOrder;

class ProductOrderRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new ProductOrder();
    }
    
}