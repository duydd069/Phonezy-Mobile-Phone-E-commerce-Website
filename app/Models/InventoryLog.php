<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    protected $table = 'inventory_logs';
    
    protected $fillable = [
        'warehouse_id',
        'product_variant_id',
        'quantity_change',
        'type',
        'reason',
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

