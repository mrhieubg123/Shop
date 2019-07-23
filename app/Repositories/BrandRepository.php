<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new Brand();
    }
    
    
    // public function getList()
    // {
    //     return $this->model::all();
    // }
}
