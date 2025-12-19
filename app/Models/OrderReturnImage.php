<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_return_id',
        'type',
        'path',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship
     */
    public function orderReturn()
    {
        return $this->belongsTo(OrderReturn::class);
    }

    /**
     * Get full URL for the image
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
