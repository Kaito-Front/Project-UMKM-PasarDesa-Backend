<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'price', 'original_price', 'category_id', 'shop_id', 'kecamatan',
        'stock', 'rating', 'sold', 'is_featured', 'is_popular', 'is_new', 'is_hidden',
        'images', 'description', 'specifications', 'reviews',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'original_price' => 'float',
            'rating' => 'float',
            'images' => 'array',
            'specifications' => 'array',
            'reviews' => 'array',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'is_new' => 'boolean',
            'is_hidden' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
