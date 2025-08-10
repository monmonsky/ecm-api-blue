<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use UUID;

    protected $fillable = [
        'id',
        'product_id',
        'image',
        'is_thumbnail',
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
