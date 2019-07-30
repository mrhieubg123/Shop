<?php

namespace App\Repositories;

use App\Models\Love;

class LoveRepository extends BaseRepository
{
    protected $model;
    
    public function __construct() {
        
        $this->model = new Love();
    }
    
    
    public function getList($id)
    {
    	return $this->model->where([['user_id',$id],['loved',1]])->with(['product'])->get();
    }
    public function getProduct($user_id,$product_id){
    	return $this->model->where([['user_id',$user_id],['product_id',$product_id]])->get();
    }
}
