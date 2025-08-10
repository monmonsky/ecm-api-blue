<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use UUID;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'phone_number',
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
