<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use UUID;

    protected $fillable = [
        'code',
        'buyer_id',
        'store_id',
        'address',
        'address_id',
        'city',
        'postal_code',
        'shipping',
        'shipping_type',
        'shipping_cost',
        'tracking_number',
        'tax',
        'grand_total',
        'payment_status',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // Define the relationship with the Buyer model
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    // Define the relationship with the Store model
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Define the relationship with TransactionItems
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Define the relationship with ProductReviews
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
