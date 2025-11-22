<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageRequest;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Storage::orderBy('storage')->paginate(15);
        return view('admin.storages.index', compact('storages'));
    }

    public function create()
    {
        return view('admin.storages.create');
    }

    public function store(StorageRequest $request)
    {
        $validated = $request->validated();

        Storage::create($validated);

        return redirect()
            ->route('admin.storages.index')
            ->with('success', 'Đã tạo dung lượng mới.');
    }

    public function edit(Storage $storage)
    {
        return view('admin.storages.edit', compact('storage'));
    }

    public function update(StorageRequest $request, Storage $storage)
    {
        $validated = $request->validated();

        $storage->update($validated);

        return redirect()
            ->route('admin.storages.index')
            ->with('success', 'Đã cập nhật dung lượng.');
    }

    public function destroy(Storage $storage)
    {
        // Load relationship để kiểm tra
        $storage->load('variants');

        // Kiểm tra xem dung lượng có đang được sử dụng không
        if ($storage->variants->count() > 0) {
            return redirect()
                ->route('admin.storages.index')
                ->with('error', 'Không thể xóa dung lượng này vì đang được sử dụng trong ' . $storage->variants->count() . ' biến thể sản phẩm.');
        }

        try {
            DB::beginTransaction();

            $storage->delete();

            DB::commit();

            return redirect()
                ->route('admin.storages.index')
                ->with('success', 'Đã xóa dung lượng.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.storages.index')
                ->with('error', 'Không thể xóa dung lượng: ' . $e->getMessage());
        }
    }
}

