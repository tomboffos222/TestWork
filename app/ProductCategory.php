<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Pivot
{
    //


    protected $fillable = [
        'product_id',
        'category_id'
    ];

}
