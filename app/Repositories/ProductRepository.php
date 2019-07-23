<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new Product();
    }
    
    
    public function getList()
    {
        return $this->model->with(['imageproducts'])
        ->paginate(9);
    }
}
