<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['slug' , 'name' , 'description' ,'meta_title', 'meta_keyword', 'meta_description', 'status'];

    public function products()
    {
       return $this->HasMany(Product::class);
    }
}
