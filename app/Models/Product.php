<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UUID;

    protected $fillable = [
        'store_id',
        'product_category_id',
        'name',
        'slug',
        'about',
        'description',
        'condition',
        'brand',
        'price',
        'weight',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Define the relationship with the Store model
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Define the relationship with ProductCategory
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    // Define the relationship with ProductImage
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Define the relationship with TransactionDetail
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Define the relationship with ProductReview
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
