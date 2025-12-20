<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category','brand'])
            ->orderBy('id','desc')
            ->limit(12)
            ->get();

        // Lấy các sản phẩm thuộc danh mục 'Phụ kiện' (nếu có)
        $accessories = collect();
        try {
            $accessoryCategory = \App\Models\Category::where('name', 'like', '%Phụ kiện%')->first();
            if ($accessoryCategory) {
                $accessories = Product::with(['category','brand'])
                    ->where('category_id', $accessoryCategory->id)
                    ->orderBy('id', 'desc')
                    ->limit(8)
                    ->get();
            }
        } catch (\Exception $e) {
            // Nếu không tìm thấy model hoặc lỗi, bỏ qua
            $accessories = collect();
        }

        return view('electro.index', compact('products', 'accessories'));
    }

    public function show(Product $product)
    {
        $product->load(['category','brand']);
        
        // Always load variants to check if product has variants
        // Load all variants first to check count
        $allVariants = $product->variants()->with(['storage', 'version', 'color', 'images'])->get();
        
        // If product has variants, load only available ones for display
        if ($allVariants->count() > 0) {
            $product->load(['variants' => function($query) {
                $query->where('status', 'available')
                      ->with(['storage', 'version', 'color', 'images'])
                      ->orderBy('price', 'asc');
            }]);
            
            // Update has_variant flag if not set but has variants
            if (!$product->has_variant) {
                $product->has_variant = true;
            }
        }
        
        // Load product images
        $product->load('images');
        
        // Get related products (same category, limit 4)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['category', 'brand'])
            ->limit(4)
            ->get();
        
        $product->increment('views');
        
        // Check if product is in user's wishlist
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('electro.product', compact('product', 'relatedProducts', 'inWishlist'));
    }

    public function promotions()
    {
        // Lấy các sản phẩm có ít nhất một variant đang được khuyến mãi (price_sale < price và price_sale không null)
        $products = Product::with(['category', 'brand', 'variants'])
            ->whereHas('variants', function($query) {
                $query->whereNotNull('price_sale')
                      ->whereColumn('price_sale', '<', 'price')
                      ->where('status', 'available');
            })
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('electro.promotions', compact('products'));
    }
}


