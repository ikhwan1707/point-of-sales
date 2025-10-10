<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customers;
use App\TransactionDetail;
use App\User;

class Transactions extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'transaction_code',
        'customer_id',
        'user_id',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'status',
        'discount',
        'transaction_date',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    // Relasi ke User (kasir/admin)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke Transaction Details
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'transaction_id');
    }
}
