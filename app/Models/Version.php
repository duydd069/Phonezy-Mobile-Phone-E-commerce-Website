<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'versions';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}

