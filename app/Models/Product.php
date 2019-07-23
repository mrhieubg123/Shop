<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cocur\Slugify\Slugify;

class Product extends Model
{
    /**
     *
     * @var array
     */
    protected $table = "products";
    protected $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'brand_id'
    ];
    /**
     * [category description]
     * @return [type] [description]
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }
    public function imageproducts()
    {
        return $this->hasMany(ImageProduct::class, 'product_id', 'id');
    }
    public function product_order()
    {
    	return $this->hasMany(ProductOrder::class,'product_id','id');
    }
    public function love()
    {
    	return $this->haveMany(Love::class,'product_id','id');
    }
    
}