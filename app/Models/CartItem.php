<?php

namespace App\Models;

<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> ce7f9d05044b5923566136beda1ee9cb8285c6bf
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
<<<<<<< HEAD
    protected $table = 'cart_items';
    
    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // public function cart()
    // {
    //     return $this->belongsTo(Cart::class);
    // }
}

=======
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
>>>>>>> ce7f9d05044b5923566136beda1ee9cb8285c6bf
