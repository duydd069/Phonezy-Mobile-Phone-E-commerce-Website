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

    /**
     * Tự động tạo SKU dựa trên thông tin sản phẩm và variant
     */
    public static function generateSku(Product $product, ?int $versionId = null, ?int $storageId = null, ?int $colorId = null): string
    {
        $parts = [];
        
        // Lấy brand code (2-3 ký tự đầu của brand name)
        if ($product->brand) {
            $brandName = strtoupper(preg_replace('/[^a-z0-9]/i', '', $product->brand->name));
            $brandCode = substr($brandName, 0, 3);
            if (empty($brandCode)) {
                $brandCode = 'PRD';
            }
            $parts[] = $brandCode;
        } else {
            $parts[] = 'PRD';
        }

        // Lấy product code từ tên sản phẩm (ví dụ: iPhone 15 -> IP15)
        $productName = strtoupper(preg_replace('/[^a-z0-9\s]/i', '', $product->name));
        // Tách số và chữ từ tên sản phẩm
        if (preg_match('/([a-z]+)\s*(\d+)/i', $productName, $matches)) {
            $productCode = substr($matches[1], 0, 2) . $matches[2];
        } elseif (preg_match('/(\d+)/', $productName, $matches)) {
            $productCode = 'P' . $matches[1];
        } else {
            $productCode = 'P' . $product->id;
        }
        $parts[] = $productCode;

        // Thêm version code nếu có
        if ($versionId) {
            $version = Version::find($versionId);
            if ($version) {
                $versionName = strtoupper(preg_replace('/[^a-z0-9]/i', '', $version->name));
                if (preg_match('/PRO/i', $versionName)) {
                    $parts[] = 'PRO';
                } elseif (preg_match('/MAX/i', $versionName)) {
                    $parts[] = 'MAX';
                } elseif (preg_match('/PLUS/i', $versionName)) {
                    $parts[] = 'PLUS';
                } else {
                    $parts[] = substr($versionName, 0, 3);
                }
            }
        }

        // Thêm storage code nếu có
        if ($storageId) {
            $storage = Storage::find($storageId);
            if ($storage) {
                // Lấy số từ storage (128GB -> 128, 256GB -> 256)
                if (preg_match('/(\d+)/', $storage->storage, $matches)) {
                    $parts[] = $matches[1];
                } else {
                    $parts[] = strtoupper(substr(preg_replace('/[^a-z0-9]/i', '', $storage->storage), 0, 3));
                }
            }
        }

        // Thêm color code nếu có
        if ($colorId) {
            $color = Color::find($colorId);
            if ($color) {
                $colorName = strtoupper(preg_replace('/[^a-z0-9]/i', '', $color->name));
                // Map một số màu phổ biến
                $colorMap = [
                    'DEN' => 'BLK', 'ĐEN' => 'BLK', 'BLACK' => 'BLK',
                    'TRANG' => 'WHT', 'TRẮNG' => 'WHT', 'WHITE' => 'WHT',
                    'XANH' => 'BLU', 'BLUE' => 'BLU',
                    'DO' => 'RED', 'ĐỎ' => 'RED', 'RED' => 'RED',
                    'VANG' => 'GLD', 'VÀNG' => 'GLD', 'GOLD' => 'GLD', 'YELLOW' => 'GLD',
                    'HONG' => 'PNK', 'HỒNG' => 'PNK', 'PINK' => 'PNK',
                    'TIM' => 'PRP', 'TÍM' => 'PRP', 'PURPLE' => 'PRP', 'VIOLET' => 'PRP',
                    'BAC' => 'SLV', 'BẠC' => 'SLV', 'SILVER' => 'SLV',
                    'XAM' => 'GRY', 'XÁM' => 'GRY', 'GRAY' => 'GRY', 'GREY' => 'GRY',
                ];
                $colorCode = $colorMap[$colorName] ?? substr($colorName, 0, 3);
                $parts[] = $colorCode;
            }
        }

        // Tạo SKU base
        $skuBase = implode('-', $parts);

        // Kiểm tra SKU đã tồn tại chưa, nếu có thì thêm số sequence
        $counter = 1;
        $sku = $skuBase;
        while (self::where('sku', $sku)->exists()) {
            $sku = $skuBase . '-' . $counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * Tự động tạo barcode dựa trên product_id và variant sequence
     */
    public static function generateBarcode(int $productId, ?int $variantId = null): string
    {
        // Tạo barcode dạng: [product_id][variant_sequence][timestamp_last_4_digits]
        // Ví dụ: product_id=1, variant thứ 3 -> 10030001 (1 + 003 + 0001)
        
        if ($variantId) {
            // Nếu đã có variant_id, dùng nó
            $sequence = str_pad($variantId, 4, '0', STR_PAD_LEFT);
        } else {
            // Đếm số variant hiện có của product này
            $variantCount = self::where('product_id', $productId)->count();
            $sequence = str_pad($variantCount + 1, 4, '0', STR_PAD_LEFT);
        }

        $productPart = str_pad($productId, 4, '0', STR_PAD_LEFT);
        $timestampPart = substr(time(), -4);
        
        $barcode = $productPart . $sequence . $timestampPart;

        // Đảm bảo barcode là 13 chữ số (chuẩn EAN-13)
        if (strlen($barcode) < 13) {
            $barcode = str_pad($barcode, 13, '0', STR_PAD_LEFT);
        } elseif (strlen($barcode) > 13) {
            $barcode = substr($barcode, 0, 13);
        }

        // Kiểm tra barcode đã tồn tại chưa
        $counter = 1;
        $originalBarcode = $barcode;
        while (self::where('barcode', $barcode)->exists()) {
            $barcode = substr($originalBarcode, 0, 12) . str_pad($counter, 1, '0', STR_PAD_LEFT);
            $counter++;
            if ($counter > 9) {
                // Nếu vẫn trùng, thêm timestamp
                $barcode = substr($originalBarcode, 0, 9) . substr(time(), -4);
                break;
            }
        }

        return $barcode;
    }
}

