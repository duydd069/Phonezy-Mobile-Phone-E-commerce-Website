<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $products = Product::with(['category','brand','variants'])
        ->when($q, function ($qr) use ($q) {
            $qr->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('slug', 'like', "%$q%")
                    ->orWhereHas('category', function ($c) use ($q) {
                        $c->where('name', 'like', "%$q%");
                    });
            });
        })

            
            ->orderBy('id','desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products','q'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.create', compact('categories','brands'));
    }

   public function store(ProductRequest $request)
{
    $data = $request->only(['name','description','category_id','brand_id']);
    $data['slug']  = \Illuminate\Support\Str::slug($request->name);
    $data['views'] = 0;
    $data['has_variant'] = true; // Luôn dùng variant (kể cả mặc định)
    $data['price'] = 0; // Product gốc không lưu giá

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    DB::transaction(function () use ($request, $data) {
        $product = \App\Models\Product::create($data);

        // Đảm bảo luôn có ít nhất 1 biến thể mặc định
        $this->ensureDefaultVariant($product, $request->input('price'));

        if ($request->hasFile('extra_images')) {
            foreach ($request->file('extra_images') as $extraImage) {
                if (!$extraImage) {
                    continue;
                }
                $galleryPath = $extraImage->store('product_images', 'public');
                $product->images()->create(['image_url' => $galleryPath]);
            }
        }
    });

    return redirect()->route('admin.products.index')->with('success','Thêm sản phẩm thành công');
}

    public function show($id)
    {
        $product = Product::with(['category','brand'])->findOrFail($id);
        
        if ($product->has_variant) {
            $product->load(['variants' => function($query) {
                $query->with(['storage', 'version', 'color']);
            }]);
        }
        
        // tăng lượt xem
        $product->increment('views');
        $product->load(['category','brand']);

        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('admin.products.edit', compact('product','categories','brands'));
    }

    public function update(ProductRequest $request, $id)
{
    $product = \App\Models\Product::findOrFail($id);

    $data = $request->only(['name','description','category_id','brand_id']);
    $data['slug'] = \Illuminate\Support\Str::slug($request->name);
    $data['has_variant'] = true;
    $data['price'] = 0; // Product gốc không lưu giá

    DB::transaction(function () use ($request, $product, $data) {
        if ($request->hasFile('image')) {
            // xoá ảnh cũ nếu là file trong storage
            if ($product->image && !str_starts_with($product->image, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        // Đảm bảo luôn có biến thể mặc định nếu chưa có
        $this->ensureDefaultVariant($product, $request->input('price'));

        $removeImageIds = $request->input('remove_images', []);
        if (!empty($removeImageIds)) {
            $imagesToDelete = $product->images()->whereIn('id', $removeImageIds)->get();
            foreach ($imagesToDelete as $image) {
                if ($image->image_url && !Str::startsWith($image->image_url, ['http://','https://'])) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }
            $product->images()->whereIn('id', $removeImageIds)->delete();
        }

        if ($request->hasFile('extra_images')) {
            foreach ($request->file('extra_images') as $extraImage) {
                if (!$extraImage) {
                    continue;
                }
                $galleryPath = $extraImage->store('product_images', 'public');
                $product->images()->create(['image_url' => $galleryPath]);
            }
        }
    });

    return redirect()->route('admin.products.index')->with('success','Cập nhật sản phẩm thành công');
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Load relationships để kiểm tra và xóa
        $product->load([
            'variants.orderItems',
            'variants.images',
            'variants.inventoryLogs',
            'variants.warehouseStock',
            'variants.cartItems',
            'images'
        ]);

        // Kiểm tra xem có variant nào đã có trong đơn hàng không
        $hasOrderItems = false;
        foreach ($product->variants as $variant) {
            if ($variant->orderItems && $variant->orderItems->count() > 0) {
                $hasOrderItems = true;
                break;
            }
        }

        if ($hasOrderItems) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Không thể xóa sản phẩm này vì đã có biến thể trong đơn hàng. Vui lòng xóa hoặc đổi trạng thái các biến thể trước.');
        }

        try {
            DB::beginTransaction();

            // Xóa product images và file ảnh
            foreach ($product->images as $image) {
                if ($image->image_url && !Str::startsWith($image->image_url, ['http://','https://'])) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }
            $product->images()->delete();

            // Xóa variants và các bản ghi liên quan
            foreach ($product->variants as $variant) {
                // Xóa variant images
                foreach ($variant->images as $image) {
                    if ($image->image_url && !Str::startsWith($image->image_url, ['http://','https://'])) {
                        Storage::disk('public')->delete($image->image_url);
                    }
                }
                $variant->images()->delete();

                // Xóa inventory logs
                $variant->inventoryLogs()->delete();

                // Xóa warehouse stock
                $variant->warehouseStock()->delete();

                // Xóa cart items
                $variant->cartItems()->delete();

                // Xóa variant image
                if ($variant->image && !Str::startsWith($variant->image, ['http://','https://'])) {
                    Storage::disk('public')->delete($variant->image);
                }

                // Xóa variant
                $variant->delete();
            }

            // Xóa product image chính
            if ($product->image && !Str::startsWith($product->image, ['http://','https://'])) {
                Storage::disk('public')->delete($product->image);
            }

            // Xóa product
            $product->delete();

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Đã xoá sản phẩm');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Không thể xóa sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Tạo biến thể mặc định khi sản phẩm chưa có biến thể.
     */
    protected function ensureDefaultVariant(Product $product, ?float $priceInput = null): void
    {
        if ($product->variants()->count() > 0) {
            return;
        }

        $product->variants()->create([
            'price'    => $priceInput ?? 0,
            'price_sale' => null,
            'stock'    => 0,
            'sku'      => 'DEFAULT-' . $product->id,
            'status'   => ProductVariant::STATUS_AVAILABLE,
        ]);
    }
}
