<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $table = 'warehouse_stock';
    
    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'stock_quantity',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // public function warehouse()
    // {
    //     return $this->belongsTo(Warehouse::class);
    // }
}

