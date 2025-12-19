<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
        'price_at_time', // Snapshot giá bán khi thêm vào giỏ
        'price_sale_at_time', // Snapshot giá khuyến mãi khi thêm vào giỏ
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'price_sale_at_time' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Giữ lại để tương thích với code cũ
    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'product_id');
    }
}
