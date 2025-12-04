<?php 

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
class CategoryController extends Controller {
        public function show($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();

    $products = $category->products()->paginate(12);

    return view('client.category.show', compact('category', 'products'));
}
}
