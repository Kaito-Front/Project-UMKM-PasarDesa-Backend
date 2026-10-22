<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'product_name', 'buyer_name', 'phone', 'total', 'qty', 'status',
        'shop_name', 'kecamatan', 'address', 'items', 'shipping_method',
        'payment_method', 'notes',
    ];

    protected function casts(): array
    {
        return ['total' => 'float', 'items' => 'array'];
    }
}
