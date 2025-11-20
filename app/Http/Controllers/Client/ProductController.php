<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category','brand'])
            ->orderBy('id','desc')
            ->limit(12)
            ->get();

        return view('electro.index', compact('products'));
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

        return view('electro.product', compact('product', 'relatedProducts'));
    }
}


