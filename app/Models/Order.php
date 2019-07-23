<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cocur\Slugify\Slugify;

class Order extends Model
{
    /**
     *
     * @var array
     */
    protected $table = "orders";
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'address',
        'order_total',
    ];
    /**
     * [category description]
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function productOrder()
    {
        return $this->hasMany(ProductOrder::class,'order_id','id');
    }

}