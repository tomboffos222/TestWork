<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    //

    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'is_publicated'
    ];



    protected $dates = ['deleted_at'];

    public function categories(){

        return $this->belongsToMany(Category::class,'product_category')->withPivot('category_id');
    }
    public function scopeOfCategory($category){

        return $this->belongsToMany(Category::class,'product_category')->where('category_id',$category);
    }
}
