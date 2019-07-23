<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cocur\Slugify\Slugify;

class ProductOrder extends Model
{
    /**
     *
     * @var array
     */
    protected $table = "product_order";
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity'
    ];
    /**
     * [category description]
     * @return [type] [description]
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

}