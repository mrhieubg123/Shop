<?php

namespace App\Repositories;

use App\Models\Love;

class LoveRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new Love();
    }
    
    
    // public function getList()
    // {
    //     return $this->model::all();
    // }
}
