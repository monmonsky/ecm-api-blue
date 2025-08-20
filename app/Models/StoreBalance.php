<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBalance extends Model
{
    use UUID, HasFactory;

    protected $fillable = [
        'store_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function scopeSearch($query, $search)
    {
        $query->whereHas('store', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return $query;
    }

    // storeBalance is owned by one store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // storeBalance can have many histories
    public function storeBalanceHistories()
    {
        return $this->hasMany(StoreBalanceHistory::class);
    }

    // storeBalance can have many withdrawals
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
