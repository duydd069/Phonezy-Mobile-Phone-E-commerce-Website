@php
    /** @var \App\Models\ProductVariant|null $variant */
    $variant = $variant ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">SKU</label>
        <input type="text" name="sku"
               class="form-control"
               value="{{ old('sku', $variant->sku ?? '') }}"
               maxlength="100"
               placeholder="Để trống để tự động tạo">
        <small class="text-muted">Để trống để hệ thống tự động tạo SKU</small>
    </div>

    <div class="col-md-4">
        <label class="form-label">Giá niêm yết (VND) *</label>
        <input type="number" name="price"
               class="form-control"
               min="0" step="1000"
               value="{{ old('price', $variant->price ?? '') }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Giá khuyến mãi (VND)</label>
        <input type="number" name="price_sale"
               class="form-control"
               min="0" step="1000"
               value="{{ old('price_sale', $variant->price_sale ?? '') }}"
               placeholder="Để trống nếu không giảm giá">
    </div>

    <div class="col-md-4">
        <label class="form-label">Dung lượng/Bộ nhớ</label>
        <select name="storage_id" class="form-select">
            <option value="">-- Chọn dung lượng --</option>
            @foreach($storages ?? [] as $storage)
                <option value="{{ $storage->id }}" @selected(old('storage_id', $variant->storage_id ?? '') == $storage->id)>
                    {{ $storage->storage }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Phiên bản</label>
        <select name="version_id" class="form-select">
            <option value="">-- Chọn phiên bản --</option>
            @foreach($versions ?? [] as $version)
                <option value="{{ $version->id }}" @selected(old('version_id', $variant->version_id ?? '') == $version->id)>
                    {{ $version->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Màu sắc</label>
        <select name="color_id" class="form-select">
            <option value="">-- Chọn màu sắc --</option>
            @foreach($colors ?? [] as $color)
                <option value="{{ $color->id }}" @selected(old('color_id', $variant->color_id ?? '') == $color->id)>
                    {{ $color->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tồn kho *</label>
        <input type="number" name="stock"
               class="form-control"
               min="0" step="1"
               value="{{ old('stock', $variant->stock ?? 0) }}"
               required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Đã bán</label>
        <input type="number" name="sold"
               class="form-control"
               min="0" step="1"
               value="{{ old('sold', $variant->sold ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Trạng thái *</label>
        <select name="status" class="form-select" required>
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $variant->status ?? '') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Barcode</label>
        <input type="text" name="barcode"
               class="form-control"
               maxlength="100"
               value="{{ old('barcode', $variant->barcode ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Mô tả</label>
        <textarea name="description"
                  class="form-control"
                  rows="4"
                  placeholder="Thông tin thêm về biến thể">{{ old('description', $variant->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Hình ảnh</label>
        <input type="file" name="image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/webp">
        @if(isset($variant) && $variant->image)
            <div class="mt-2">
                <img src="{{ preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image) }}" 
                     alt="Variant image" 
                     style="max-width: 200px; max-height: 200px; object-fit: contain; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                <p class="text-muted small mt-1">Ảnh hiện tại (upload ảnh mới để thay thế)</p>
            </div>
        @endif
        <small class="text-muted">Định dạng: JPG, PNG, WEBP. Kích thước tối đa: 4MB</small>
    </div>
</div>

