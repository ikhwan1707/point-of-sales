<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Products;

class Categories extends Model
{
   protected $table = 'categories';
   protected $primaryKey = 'categorie_id';
   protected $fillable = ['name', 'description'];

   public function products()
   {
      return $this->hasMany(Products::class, 'categorie_id');
   }
}