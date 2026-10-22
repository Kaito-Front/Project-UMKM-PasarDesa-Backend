<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'name', 'owner', 'category', 'category_slug', 'kecamatan', 'address',
        'lat', 'lng', 'phone', 'whatsapp', 'rating', 'review_count', 'join_date',
        'operating_hours', 'description', 'avatar', 'banner', 'status', 'verified',
    ];

    protected function casts(): array
    {
        return ['lat' => 'float', 'lng' => 'float', 'rating' => 'float', 'verified' => 'boolean'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
