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
        $product->load(['category','brand', 'comments.user', 'comments.replies.user']);
        $product->increment('views');

        return view('electro.product', compact('product'));
    }
}


