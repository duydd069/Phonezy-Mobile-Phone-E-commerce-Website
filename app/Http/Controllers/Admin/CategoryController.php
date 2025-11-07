<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stringable;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        
          $query = Category::query();

    if ($search = $request->q) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
    }

    $categories = $query->orderBy('id', 'desc')->paginate(10);

    return view('admin.categories.index', compact('categories'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    // Lưu dữ liệu từ form vào database
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        // Tạo slug tự động từ name
        $slug = Str::slug($request->name);

        // Tạo mới category
        Category::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        // Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    public function show(Category $category)
{
    return view('admin.categories.show', compact('category'));
}

public function edit(Category $category)
{
    return view('admin.categories.edit', compact('category'));
}

public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
        'description' => 'nullable|string',
    ]);

    $data = $request->only('name', 'slug', 'description');
    if (empty($data['slug'])) {
        $data['slug'] = Str::slug($data['name']);
    }

    $category->update($data);

    return redirect()->route('categories.index')->with('success', 'update thành công danh mục!');
}

public function destroy(Category $category)
{
    $category->delete();
    return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
}

}
