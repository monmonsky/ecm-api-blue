<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use UUID;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'about',
        'phone',
        'address_id',
        'city',
        'address',
        'postal_code',
        'is_verified',
    ];

    // relationship one store owner by one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // relationship one store has one balance
    public function storeBalance()
    {
        return $this->hasOne(StoreBallance::class);
    }

    // relationship one store has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // relationship one store has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
