<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new Category();
    }
    
    
    // public function getList()
    // {
    //     return $this->model::all();
    // }
}
