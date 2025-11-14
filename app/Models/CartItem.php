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
        'product_variant_id', // mình dùng cột này để lưu product_id
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Ở đây coi product_variant_id chính là product_id
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_variant_id');
    }
}
