<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cocur\Slugify\Slugify;

class ImageProduct extends Model
{
    /**
     *
     * @var array
     */
    protected $table = "image_products";
    protected $fillable = [
        'product_id',
        'image_url'
    ];
    
    
    
    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}