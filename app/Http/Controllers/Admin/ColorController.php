<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColorRequest;
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

    public function store(ColorRequest $request)
    {
        $validated = $request->validated();

        Color::create($validated);

        return redirect()
            ->route('admin.colors.index')
            ->with('success', 'Đã tạo màu sắc mới.');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(ColorRequest $request, Color $color)
    {
        $validated = $request->validated();

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

