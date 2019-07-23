<?php

namespace App\Repositories;

use App\Models\ImageProduct;

class ImageProductRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new ImageProduct();
    }
    
    public function getImg($id){
    	return $this->model->where('product_id',$id)->get();
    }
    // public function getList()
    // {
    //     return $this->model::all();
    // }
}
