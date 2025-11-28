<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'quantity',
        'price_each',
        'variant_sku',
        'variant_volume_ml',
        'variant_description',
        'variant_status',
        'variant_name',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        // Nếu product_variant_id thực sự là product_id (như trong CartItem)
        return $this->belongsTo(Product::class, 'product_variant_id');
    }
}

