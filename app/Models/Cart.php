<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'product_qty'];
    
    //for js relationship
    protected $with = 'products';

    //for laravel or blade relationship
    public function products(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
