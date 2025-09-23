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
      return $this->belongsTo(Products::class, 'categorie_id', 'categorie_id');
   }
}