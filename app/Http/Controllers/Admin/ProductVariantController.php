<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Storage;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as StorageFacade;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function index(int $productId)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($productId);
        $variants = $product->variants()
            ->with(['storage', 'version', 'color'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.product_variants.index', compact('product', 'variants'));
    }

    public function create(int $productId)
    {
        $product = Product::findOrFail($productId);

        // Cache lookup data as they don't change frequently
        $colors = cache()->remember('colors.all', now()->addHours(24), function() {
            return Color::orderBy('name')->get();
        });
        $storages = cache()->remember('storages.all', now()->addHours(24), function() {
            return Storage::orderBy('storage')->get();
        });
        $versions = cache()->remember('versions.all', now()->addHours(24), function() {
            return Version::orderBy('name')->get();
        });
        
        return view('admin.product_variants.create', [
            'product'       => $product,
            'statusOptions' => ProductVariant::statusOptions(),
            'colors'        => $colors,
            'storages'      => $storages,
            'versions'      => $versions,
        ]);
    }

    public function store(ProductVariantRequest $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $this->processValidatedData($request, $product);
        $validated['product_id'] = $product->id;

        ProductVariant::create($validated);

        return redirect()
            ->route('admin.products.variants.index', $product->id)
            ->with('success', 'Đã tạo biến thể mới.');
    }

    public function edit(int $productId, int $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $this->findVariantOrFail($product->id, $variantId);
        $variant->load(['storage', 'version', 'color']);

        // Cache lookup data
        $colors = cache()->remember('colors.all', now()->addHours(24), function() {
            return Color::orderBy('name')->get();
        });
        $storages = cache()->remember('storages.all', now()->addHours(24), function() {
            return Storage::orderBy('storage')->get();
        });
        $versions = cache()->remember('versions.all', now()->addHours(24), function() {
            return Version::orderBy('name')->get();
        });
        
        return view('admin.product_variants.edit', [
            'product'       => $product,
            'variant'       => $variant,
            'statusOptions' => ProductVariant::statusOptions(),
            'colors'        => $colors,
            'storages'      => $storages,
            'versions'      => $versions,
        ]);
    }

    public function update(ProductVariantRequest $request, int $productId, int $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $this->findVariantOrFail($product->id, $variantId);

            $validated = $this->processValidatedData($request, $product, $variant);

        // Handle image update - delete old image if new one is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if exists and is not external URL
            if ($variant->image && !str_starts_with($variant->image, 'http')) {
                StorageFacade::disk('public')->delete($variant->image);
            }
        } else {
            // Keep existing image if no new image uploaded
            unset($validated['image']);
        }

        $variant->update($validated);

        return redirect()
            ->route('admin.products.variants.index', $product->id)
            ->with('success', 'Đã cập nhật biến thể.');
    }

    public function destroy(int $productId, int $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $this->findVariantOrFail($product->id, $variantId);
        
        // Load relationships để kiểm tra
        $variant->load(['images', 'inventoryLogs', 'warehouseStock', 'cartItems', 'orderItems']);

        // Kiểm tra xem variant có đang được sử dụng trong đơn hàng không
        if ($variant->orderItems->count() > 0) {
            return redirect()
                ->route('admin.products.variants.index', $product->id)
                ->with('error', 'Không thể xóa biến thể này vì đã có trong đơn hàng. Vui lòng đổi trạng thái thành "Ngừng kinh doanh" thay vì xóa.');
        }

        // Kiểm tra xem đây có phải là variant cuối cùng không
        $variantCount = $product->variants()->count();
        if ($variantCount <= 1) {
            return redirect()
                ->route('admin.products.variants.index', $product->id)
                ->with('error', 'Không thể xóa biến thể cuối cùng. Sản phẩm phải có ít nhất một biến thể. Vui lòng tạo biến thể mới trước khi xóa biến thể này.');
        }

        // Xóa các bản ghi liên quan trước
        try {
            DB::beginTransaction();

            // Xóa variant images và file ảnh
            foreach ($variant->images as $image) {
                if ($image->image_url && !str_starts_with($image->image_url, 'http')) {
                    StorageFacade::disk('public')->delete($image->image_url);
                }
            }
            $variant->images()->delete();

            // Xóa inventory logs
            $variant->inventoryLogs()->delete();

            // Xóa warehouse stock
            $variant->warehouseStock()->delete();

            // Xóa cart items (nếu có trong giỏ hàng)
            $variant->cartItems()->delete();

            // Delete variant image if exists and is not external URL
            if ($variant->image && !str_starts_with($variant->image, 'http')) {
                StorageFacade::disk('public')->delete($variant->image);
            }

            // Xóa variant
            $variant->delete();

            DB::commit();

            return redirect()
                ->route('admin.products.variants.index', $product->id)
                ->with('success', 'Đã xoá biến thể.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.products.variants.index', $product->id)
                ->with('error', 'Không thể xóa biến thể: ' . $e->getMessage());
        }
    }

    protected function processValidatedData(ProductVariantRequest $request, Product $product, ?ProductVariant $variant = null): array
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product_variants', 'public');
        }

        // Normalise optional fields
        $data['storage_id'] = $data['storage_id'] ?? null;
        $data['version_id'] = $data['version_id'] ?? null;
        $data['color_id'] = $data['color_id'] ?? null;
        $data['price_sale'] = $data['price_sale'] ?? null;
        $data['sold'] = $data['sold'] ?? 0;

        // Tự động tạo SKU nếu không được cung cấp
        // Nếu đang update và không có SKU mới, giữ SKU cũ
        if (empty($data['sku'])) {
            if ($variant && $variant->sku) {
                // Giữ SKU cũ khi update
                $data['sku'] = $variant->sku;
            } else {
                // Tạo SKU mới khi tạo variant mới
                $data['sku'] = $this->generateSku($product, $data, $variant);
            }
        }

        // Tự động cập nhật status dựa trên stock nếu chưa được set hoặc đang ở trạng thái available/out_of_stock
        if (!isset($data['status']) || in_array($data['status'], [ProductVariant::STATUS_AVAILABLE, ProductVariant::STATUS_OUT_OF_STOCK])) {
            $stock = $data['stock'] ?? 0;
            if ($stock <= 0) {
                $data['status'] = ProductVariant::STATUS_OUT_OF_STOCK;
            } else {
                $data['status'] = ProductVariant::STATUS_AVAILABLE;
            }
        }

        return $data;
    }

    /**
     * Tự động tạo SKU cho biến thể sản phẩm
     */
    protected function generateSku(Product $product, array $data, ?ProductVariant $variant = null): string
    {
        $parts = ['P' . $product->id];

        // Thêm storage code nếu có
        if (!empty($data['storage_id'])) {
            $storage = Storage::find($data['storage_id']);
            if ($storage) {
                // Lấy số từ storage (ví dụ: "128GB" -> "128", "256GB" -> "256")
                preg_match('/\d+/', $storage->storage, $matches);
                $storageCode = $matches[0] ?? substr(strtoupper($storage->storage), 0, 3);
                $parts[] = $storageCode;
            }
        }

        // Thêm version code nếu có
        if (!empty($data['version_id'])) {
            $version = Version::find($data['version_id']);
            if ($version) {
                // Lấy 3 ký tự đầu của version name, viết hoa
                $versionCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $version->name), 0, 3));
                if (empty($versionCode)) {
                    $versionCode = 'V' . $version->id;
                }
                $parts[] = $versionCode;
            }
        }

        // Thêm color code nếu có
        if (!empty($data['color_id'])) {
            $color = Color::find($data['color_id']);
            if ($color) {
                // Lấy 2-3 ký tự đầu của color name, viết hoa
                $colorCode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $color->name), 0, 3));
                if (empty($colorCode)) {
                    $colorCode = 'C' . $color->id;
                }
                $parts[] = $colorCode;
            }
        }

        // Nếu không có attributes, dùng số thứ tự variant
        if (count($parts) === 1) {
            $variantCount = $product->variants()->count();
            // Nếu đang update, không tính variant hiện tại
            if ($variant) {
                $variantCount--;
            }
            $parts[] = 'V' . ($variantCount + 1);
        }

        $baseSku = implode('-', $parts);
        $sku = $baseSku;
        $counter = 1;

        // Đảm bảo SKU unique
        while (ProductVariant::where('sku', $sku)
            ->when($variant, function ($query) use ($variant) {
                return $query->where('id', '!=', $variant->id);
            })
            ->exists()) {
            $sku = $baseSku . '-' . $counter;
            $counter++;
        }

        return $sku;
    }

    /**
     * Cập nhật số lượng tồn kho của biến thể (AJAX)
     */
    public function updateStock(Request $request, int $productId, int $variantId)
    {
        try {
            $product = Product::findOrFail($productId);
            $variant = $this->findVariantOrFail($product->id, $variantId);

            $request->validate([
                'stock' => 'required|integer|min:0',
            ]);

            $newStock = (int) $request->stock;
            $oldStatus = $variant->status;

            // Cập nhật stock
            $variant->update([
                'stock' => $newStock,
            ]);

            // Tự động cập nhật status dựa trên stock
            $newStatus = $oldStatus;
            if ($newStock <= 0) {
                if ($oldStatus === ProductVariant::STATUS_AVAILABLE) {
                    $variant->update(['status' => ProductVariant::STATUS_OUT_OF_STOCK]);
                    $newStatus = ProductVariant::STATUS_OUT_OF_STOCK;
                }
            } else {
                // Nếu stock > 0 và đang ở trạng thái hết hàng, chuyển về available
                if ($oldStatus === ProductVariant::STATUS_OUT_OF_STOCK) {
                    $variant->update(['status' => ProductVariant::STATUS_AVAILABLE]);
                    $newStatus = ProductVariant::STATUS_AVAILABLE;
                }
            }

            // Refresh variant để lấy dữ liệu mới nhất
            $variant->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng tồn kho thành công.',
                'stock' => $variant->stock,
                'status' => $variant->status,
                'status_label' => ProductVariant::statusOptions()[$variant->status] ?? $variant->status,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->errors()['stock'] ?? []),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật số lượng tồn kho: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function findVariantOrFail(int $productId, int $variantId): ProductVariant
    {
        return ProductVariant::with(['storage', 'version', 'color'])
            ->where('product_id', $productId)
            ->where('id', $variantId)
            ->firstOrFail();
    }
}

