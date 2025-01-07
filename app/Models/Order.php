<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_SHIPPED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED = 4;

    protected $fillable = [
        'user_id',
        'order_id',
        'shipping_address_id',
        'status',
        'total_price',
    ];

    protected $appends = [
        'status_label',
        'formatted_total_price'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(OrderShippingAddress::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public static $statuses = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PAID => 'Paid',
        self::STATUS_SHIPPED => 'Shipped',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELED => 'Canceled',
    ];

    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? 'Unknown';
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp' . number_format($this->total_price, 2, ',', '.');
    }
}
