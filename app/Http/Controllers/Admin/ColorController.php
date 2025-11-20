<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('name')->paginate(15);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:colors,name',
            'hex_code' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/',
            ],
        ]);

        Color::create($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Đã tạo màu sắc mới.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:colors,name,' . $color->id,
            'hex_code' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/',
            ],
        ]);

        $color->update($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Đã cập nhật màu sắc.');
    }

    public function destroy(Color $color)
    {
        // Load relationship để kiểm tra
        $color->load('variants');

        // Kiểm tra xem màu có đang được sử dụng không
        if ($color->variants->count() > 0) {
            return redirect()
                ->route('admin.colors.index')
                ->with('error', 'Không thể xóa màu này vì đang được sử dụng trong ' . $color->variants->count() . ' biến thể sản phẩm.');
        }

        try {
            DB::beginTransaction();

            $color->delete();

            DB::commit();

            return redirect()
                ->route('admin.colors.index')
                ->with('success', 'Đã xóa màu sắc.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.colors.index')
                ->with('error', 'Không thể xóa màu sắc: ' . $e->getMessage());
        }
    }
}

