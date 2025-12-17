<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;

class ProductController extends Controller
{
    public function index()
    {
        // Load variants for price display, but only first variant to reduce data
        $products = Product::with([
            'category',
            'brand',
            'variants' => function($query) {
                $query->where('status', 'available')
                      ->orderBy('price', 'asc')
                      ->limit(1); // Only need first variant for price
            }
        ])
            ->orderBy('id','desc')
            ->limit(12)
            ->get();

        return view('electro.index', compact('products'));
    }

    public function show(Product $product)
    {
        // Load all relationships in one query to avoid N+1
        $product->load([
            'category',
            'brand',
            'images',
            'variants' => function($query) {
                $query->where('status', 'available')
                      ->with(['storage', 'version', 'color', 'images'])
                      ->orderBy('price', 'asc');
            }
        ]);
        
        // Update has_variant flag if not set but has variants
        if ($product->variants->count() > 0 && !$product->has_variant) {
            $product->has_variant = true;
        }
        
        // Get related products (same category, limit 4) - cache this query
        $relatedProducts = cache()->remember(
            "product.{$product->id}.related",
            now()->addHours(1),
            function () use ($product) {
                return Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->with(['category', 'brand'])
                    ->limit(4)
                    ->get();
            }
        );
        
        // Increment views asynchronously to not block page load
        dispatch(function() use ($product) {
            $product->increment('views');
        })->afterResponse();
        
        // Check if product is in user's wishlist (only if logged in)
        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = cache()->remember(
                "wishlist.user." . auth()->id() . ".product.{$product->id}",
                now()->addMinutes(5),
                function () use ($product) {
                    return Wishlist::where('user_id', auth()->id())
                        ->where('product_id', $product->id)
                        ->exists();
                }
            );
        }

        return view('electro.product', compact('product', 'relatedProducts', 'inWishlist'));
    }

    public function promotions()
    {
        // Lấy các sản phẩm có ít nhất một variant đang được khuyến mãi (price_sale < price và price_sale không null)
        // Only load first variant for price display
        $products = Product::with([
            'category', 
            'brand', 
            'variants' => function($query) {
                $query->whereNotNull('price_sale')
                      ->whereColumn('price_sale', '<', 'price')
                      ->where('status', 'available')
                      ->orderBy('price_sale', 'asc')
                      ->limit(1); // Only need first variant for price
            }
        ])
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


