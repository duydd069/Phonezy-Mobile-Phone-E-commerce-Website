<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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

        $products = Product::with(['category','brand'])
            ->when($q, function($qr) use ($q) {
                $qr->where('name','like',"%$q%")
                   ->orWhere('slug','like',"%$q%");
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

   public function store(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:200',
        'price'       => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'brand_id'    => 'required|exists:brands,id',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'description' => 'nullable|string',
        'extra_images' => 'nullable|array|max:10',
        'extra_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
    ]);

    $data = $request->only(['name','price','description','category_id','brand_id']);
    $data['slug']  = \Illuminate\Support\Str::slug($request->name);
    $data['views'] = 0;

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product = \App\Models\Product::create($data);

    if ($request->hasFile('extra_images')) {
        foreach ($request->file('extra_images') as $extraImage) {
            if (!$extraImage) {
                continue;
            }
            $galleryPath = $extraImage->store('product_images', 'public');
            $product->images()->create(['image_url' => $galleryPath]);
        }
    }

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

    public function update(Request $request, $id)
{
    $product = \App\Models\Product::findOrFail($id);

    $request->validate([
        'name'        => 'required|string|max:200',
        'price'       => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'brand_id'    => 'required|exists:brands,id',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'description' => 'nullable|string',
        'extra_images' => 'nullable|array|max:10',
        'extra_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        'remove_images' => 'nullable|array',
        'remove_images.*' => 'integer|exists:product_images,id',
    ]);

    $data = $request->only(['name','price','description','category_id','brand_id']);
    $data['slug'] = \Illuminate\Support\Str::slug($request->name);

    if ($request->hasFile('image')) {
        // xoá ảnh cũ nếu là file trong storage
        if ($product->image && !str_starts_with($product->image, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

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
}
