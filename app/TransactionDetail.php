<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transactions;
use App\Products;
class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    protected $primaryKey = 'transaction_detail_id';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relasi ke Transaction
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id', 'transaction_id');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}