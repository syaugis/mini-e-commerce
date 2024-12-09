<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'address',
        'city',
        'postcode',
        'phone',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
