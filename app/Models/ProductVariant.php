<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Version;
use App\Models\Storage;
use App\Models\Color;

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

    public const STATUS_AVAILABLE    = 'available';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';
    public const STATUS_DISCONTINUED = 'discontinued';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_AVAILABLE    => 'Còn hàng',
            self::STATUS_OUT_OF_STOCK => 'Hết hàng',
            self::STATUS_DISCONTINUED => 'Ngừng kinh doanh',
        ];
    }

    /* =========================
     | Relationships
     ========================= */

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

    /**
     * 🔴 FIX CHÍNH Ở ĐÂY
     * order_items KHÔNG có product_variant_id
     * → map theo product_id
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }

    /* =========================
     | Accessors
     ========================= */

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

    /* =========================
     | SKU & BARCODE
     ========================= */

    public static function generateSku(
        Product $product,
        ?int $versionId = null,
        ?int $storageId = null,
        ?int $colorId = null
    ): string {
        $parts = [];

        // Brand code
        if ($product->brand) {
            $brandName = strtoupper(preg_replace('/[^a-z0-9]/i', '', $product->brand->name));
            $parts[] = substr($brandName, 0, 3) ?: 'PRD';
        } else {
            $parts[] = 'PRD';
        }

        // Product code
        $productName = strtoupper(preg_replace('/[^a-z0-9\s]/i', '', $product->name));
        if (preg_match('/([a-z]+)\s*(\d+)/i', $productName, $m)) {
            $parts[] = substr($m[1], 0, 2) . $m[2];
        } elseif (preg_match('/(\d+)/', $productName, $m)) {
            $parts[] = 'P' . $m[1];
        } else {
            $parts[] = 'P' . $product->id;
        }

        // Version
        if ($versionId && ($version = Version::find($versionId))) {
            $name = strtoupper($version->name);
            $parts[] = str_contains($name, 'PRO') ? 'PRO'
                    : (str_contains($name, 'MAX') ? 'MAX'
                    : (str_contains($name, 'PLUS') ? 'PLUS'
                    : substr($name, 0, 3)));
        }

        // Storage
        if ($storageId && ($storage = Storage::find($storageId))) {
            preg_match('/(\d+)/', $storage->storage, $m);
            $parts[] = $m[1] ?? substr($storage->storage, 0, 3);
        }

        // Color
        if ($colorId && ($color = Color::find($colorId))) {
            $map = [
                'BLACK' => 'BLK', 'ĐEN' => 'BLK',
                'WHITE' => 'WHT', 'TRẮNG' => 'WHT',
                'BLUE'  => 'BLU', 'XANH' => 'BLU',
                'RED'   => 'RED', 'ĐỎ' => 'RED',
                'GOLD'  => 'GLD', 'VÀNG' => 'GLD',
                'SILVER'=> 'SLV', 'BẠC' => 'SLV',
            ];
            $name = strtoupper($color->name);
            $parts[] = $map[$name] ?? substr($name, 0, 3);
        }

        $base = implode('-', $parts);
        $sku  = $base;
        $i = 1;

        while (self::where('sku', $sku)->exists()) {
            $sku = $base . '-' . $i++;
        }

        return $sku;
    }

    public static function generateBarcode(int $productId): string
    {
        $productPart   = str_pad($productId, 4, '0', STR_PAD_LEFT);
        $variantCount  = self::where('product_id', $productId)->count() + 1;
        $variantPart   = str_pad($variantCount, 4, '0', STR_PAD_LEFT);
        $timestampPart = substr(time(), -5);

        return substr($productPart . $variantPart . $timestampPart, 0, 13);
    }
}
