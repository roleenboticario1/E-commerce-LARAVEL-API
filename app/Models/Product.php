<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
      'category_id',
      'slug',
      'name',
      'description',
      'meta_title',
      'meta_keyword',
      'meta_description',
      'selling_price',
      'original_price',
      'qty',
      'brand',
      'featured',
      'popular',
      'status',
    ];
    
    //You need this for JS or JS Framework //for js relationship
    protected $with = ['categories'];
   
    //for laravel or blade relationship
    public function categories()
    {
       return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
