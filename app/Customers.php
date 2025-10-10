<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transactions;

class Customers extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = ['name', 'phone', 'address', 'is_member'];

    // Relasi ke Transaction
    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'customer_id', 'customer_id');
    }
}
