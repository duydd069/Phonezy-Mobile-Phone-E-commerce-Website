<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        'gender'      => 'nullable|in:male,female,unisex',
        // chỉ còn validate file ảnh
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'description' => 'nullable|string',
    ]);

    $data = $request->only(['name','price','description','gender','category_id','brand_id']);
    $data['slug']  = \Illuminate\Support\Str::slug($request->name);
    $data['views'] = 0;

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    \App\Models\Product::create($data);

    return redirect()->route('admin.products.index')->with('success','Thêm sản phẩm thành công');
}

    public function show($id)
    {
        $product = Product::with(['category','brand'])->findOrFail($id);
        // tăng lượt xem
        $product->increment('views');
        $product->refresh();

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
        'gender'      => 'nullable|in:male,female,unisex',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        'description' => 'nullable|string',
    ]);

    $data = $request->only(['name','price','description','gender','category_id','brand_id']);
    $data['slug'] = \Illuminate\Support\Str::slug($request->name);

    if ($request->hasFile('image')) {
        // xoá ảnh cũ nếu là file trong storage
        if ($product->image && !str_starts_with($product->image, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('admin.products.index')->with('success','Cập nhật sản phẩm thành công');
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && !Str::startsWith($product->image, ['http://','https://'])) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xoá sản phẩm');
    }
}
