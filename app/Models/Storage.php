<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    protected $table = 'storages';
    
    public $timestamps = false;
    
    protected $fillable = [
        'storage',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

