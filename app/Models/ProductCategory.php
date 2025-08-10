<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use UUID;

    protected $fillable = [
        'parent_id',
        'image',
        'name',
        'slug',
        'tagline',
        'description',
    ];

    // Define the relationship with parent category
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // Define the relationship with child categories
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    // Define the relationship with products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
