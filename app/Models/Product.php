<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name','image','price','slug','description','has_variant','category_id','brand_id','views'
    ];

    protected $casts = [
        'has_variant' => 'boolean',
        'views' => 'integer',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function brand()    { return $this->belongsTo(Brand::class); }
    public function comments() { return $this->hasMany(Comment::class)->whereNull('parent_id')->orderBy('created_at', 'desc'); }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function getAvgRatingAttribute()
    {
        return round($this->comments()->whereNotNull('rating')->avg('rating'), 1) ?: 5.0; // Default 5 if no ratings
    }

    public function getCountRatingAttribute()
    {
        return $this->comments()->whereNotNull('rating')->count();
    }
}
