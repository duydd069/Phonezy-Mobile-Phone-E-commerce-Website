<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'price',
        'price_sale',
        'stock',
        'sold',
        'sku',
        'barcode',
        'storage_id',
        'version_id',
        'color_id',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'price'      => 'decimal:0',
        'price_sale' => 'decimal:0',
        'stock'      => 'integer',
        'sold'       => 'integer',
        'storage_id' => 'integer',
        'version_id' => 'integer',
        'color_id'   => 'integer',
    ];

    public const STATUS_AVAILABLE      = 'available';
    public const STATUS_OUT_OF_STOCK   = 'out_of_stock';
    public const STATUS_DISCONTINUED   = 'discontinued';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_AVAILABLE    => 'Còn hàng',
            self::STATUS_OUT_OF_STOCK => 'Hết hàng',
            self::STATUS_DISCONTINUED => 'Ngừng kinh doanh',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function version()
    {
        return $this->belongsTo(Version::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function images()
    {
        return $this->hasMany(VariantImage::class, 'variant_id');
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class, 'product_variant_id');
    }

    public function warehouseStock()
    {
        return $this->hasMany(WarehouseStock::class, 'product_variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.');
    }

    public function getDisplaySalePriceAttribute(): ?string
    {
        return $this->price_sale !== null
            ? number_format($this->price_sale, 0, ',', '.')
            : null;
    }
}

