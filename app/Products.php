<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Categories;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'status',
        'categorie_id',
    ];

    // Relasi ke Category
    public function categories()
    {
        return $this->belongsTo(Categories::class, 'categorie_id', 'categorie_id');
    }
}
