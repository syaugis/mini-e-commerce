<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFirstImageUrlAttribute(): string
    {
        return url('storage/' . $this->image_path);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? Storage::url($this->image_path)
            : asset('images/error/no_image.png');
    }
}
