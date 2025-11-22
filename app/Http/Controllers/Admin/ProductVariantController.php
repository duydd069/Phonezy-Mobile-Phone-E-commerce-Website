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

        return view('admin.product_variants.create', [
            'product'       => $product,
            'statusOptions' => ProductVariant::statusOptions(),
            'colors'        => Color::orderBy('name')->get(),
            'storages'      => Storage::orderBy('storage')->get(),
            'versions'      => Version::orderBy('name')->get(),
        ]);
    }

    public function store(ProductVariantRequest $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $validated = $this->processValidatedData($request);
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

        return view('admin.product_variants.edit', [
            'product'       => $product,
            'variant'       => $variant,
            'statusOptions' => ProductVariant::statusOptions(),
            'colors'        => Color::orderBy('name')->get(),
            'storages'      => Storage::orderBy('storage')->get(),
            'versions'      => Version::orderBy('name')->get(),
        ]);
    }

    public function update(ProductVariantRequest $request, int $productId, int $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $this->findVariantOrFail($product->id, $variantId);

        $validated = $this->processValidatedData($request);

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

    protected function processValidatedData(ProductVariantRequest $request): array
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

        return $data;
    }

    protected function findVariantOrFail(int $productId, int $variantId): ProductVariant
    {
        return ProductVariant::with(['storage', 'version', 'color'])
            ->where('product_id', $productId)
            ->where('id', $variantId)
            ->firstOrFail();
    }
}

