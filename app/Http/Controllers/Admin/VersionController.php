<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VersionController extends Controller
{
    public function index()
    {
        $versions = Version::orderBy('name')->paginate(15);
        return view('admin.versions.index', compact('versions'));
    }

    public function create()
    {
        return view('admin.versions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:versions,name',
        ]);

        Version::create($validated);

        return redirect()
            ->route('admin.versions.index')
            ->with('success', 'Đã tạo phiên bản mới.');
    }

    public function edit(Version $version)
    {
        return view('admin.versions.edit', compact('version'));
    }

    public function update(Request $request, Version $version)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:versions,name,' . $version->id,
        ]);

        $version->update($validated);

        return redirect()
            ->route('admin.versions.index')
            ->with('success', 'Đã cập nhật phiên bản.');
    }

    public function destroy(Version $version)
    {
        // Load relationship để kiểm tra
        $version->load('variants');

        // Kiểm tra xem phiên bản có đang được sử dụng không
        if ($version->variants->count() > 0) {
            return redirect()
                ->route('admin.versions.index')
                ->with('error', 'Không thể xóa phiên bản này vì đang được sử dụng trong ' . $version->variants->count() . ' biến thể sản phẩm.');
        }

        try {
            DB::beginTransaction();

            $version->delete();

            DB::commit();

            return redirect()
                ->route('admin.versions.index')
                ->with('success', 'Đã xóa phiên bản.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.versions.index')
                ->with('error', 'Không thể xóa phiên bản: ' . $e->getMessage());
        }
    }
}

