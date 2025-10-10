<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categories;

class Productss extends Model
{
    protected $table = "products";
    protected $primaryKey = "product_id";
    protected $fillable = ['categorie_id','name','description','price','stock','image','status'];

    public function categories(){
        return $this->belongsTo(Categories::class,'categorie_id');
    }
}