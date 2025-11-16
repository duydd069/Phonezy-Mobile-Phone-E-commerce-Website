<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name','image','price','slug','description','gender','category_id','brand_id','views'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand()    { return $this->belongsTo(Brand::class); }
    public function comments() { return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc'); }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
