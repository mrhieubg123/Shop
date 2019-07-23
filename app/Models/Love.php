<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cocur\Slugify\Slugify;

class Love extends Model
{
    /**
     *
     * @var array
     */
    protected $table = "loves";
    protected $fillable = [
        'user_id',
        'product_id',
        'loved',
    ];
    /**
     * [category description]
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product')
    }

}