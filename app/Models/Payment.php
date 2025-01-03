<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'snap_token',
        'status',
        'paid_at',
    ];

    protected $appends = [
        'snap_url',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getSnapUrlAttribute(): String
    {
        return 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $this->snap_token;
    }
}
