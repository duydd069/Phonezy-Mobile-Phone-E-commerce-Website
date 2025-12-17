<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller {
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        // Only load first variant for price display to reduce data
        $products = Product::where('category_id', $category->id)
            ->with([
                'category', 
                'brand', 
                'variants' => function($query) {
                    $query->where('status', 'available')
                          ->orderBy('price', 'asc')
                          ->limit(1); // Only need first variant for price
                }
            ])
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('client.category.show', compact('category', 'products'));
    }
}
