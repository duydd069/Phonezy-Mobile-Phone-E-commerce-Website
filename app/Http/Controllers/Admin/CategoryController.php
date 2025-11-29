<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
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
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $slug = $validated['slug'] ?? null;
        $slug = Str::slug($slug ?: $validated['name']);

        // Tạo mới category
        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        // Chuyển hướng về trang danh sách kèm thông báo
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    public function show(Category $category)
{
    return view('admin.categories.show', compact('category'));
}

public function edit(Category $category)
{
    return view('admin.categories.edit', compact('category'));
}

public function update(CategoryRequest $request, Category $category)
{
    $data = $request->validated();
    if (empty($data['slug'])) {
        $data['slug'] = Str::slug($data['name']);
    }

    $category->update($data);

    return redirect()->route('admin.categories.index')->with('success', 'update thành công danh mục!');
}

public function destroy(Category $category)
{
    $category->delete();
    return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
}

}
