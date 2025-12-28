@php
    /** @var \App\Models\ProductVariant|null $variant */
    $variant = $variant ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">SKU <small class="text-muted">(để trống để tự động tạo)</small></label>
        <div class="input-group">
            <input type="text" name="sku" id="sku-input"
                   class="form-control"
                   value="{{ old('sku', $variant->sku ?? '') }}"
                   maxlength="100"
                   placeholder="Tự động tạo khi lưu">
            <button type="button" class="btn btn-outline-secondary" id="generate-sku-btn" title="Tự động tạo SKU">
                <i class="bi bi-arrow-clockwise"></i> Tạo
            </button>
        </div>
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
        <label class="form-label">Đã bán <small class="text-muted">(tự động tính từ đơn hàng)</small></label>
        <input type="number"
               class="form-control"
               value="{{ isset($variant) ? $variant->actual_sold : 0 }}"
               readonly
               style="background-color: #f8f9fa; cursor: not-allowed;"
               title="Số lượng này được tự động tính từ các đơn hàng đã thanh toán">
        <input type="hidden" name="sold" value="{{ old('sold', $variant->sold ?? 0) }}">
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
        <label class="form-label">Barcode <small class="text-muted">(mỗi sản phẩm chỉ có 1 mã, để trống để tự động tạo)</small></label>
        <div class="input-group">
            <input type="text" name="barcode" id="barcode-input"
                   class="form-control"
                   maxlength="100"
                   value="{{ old('barcode', $variant->barcode ?? '') }}"
                   placeholder="Tự động tạo khi lưu (sử dụng lại nếu sản phẩm đã có)">
            <button type="button" class="btn btn-outline-secondary" id="generate-barcode-btn" title="Tự động tạo Barcode">
                <i class="bi bi-arrow-clockwise"></i> Tạo
            </button>
        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy productId từ URL hoặc từ biến PHP
    let productId = null;
    const urlParts = window.location.pathname.split('/');
    const productIdIndex = urlParts.indexOf('products') + 1;
    if (productIdIndex > 0 && urlParts[productIdIndex]) {
        productId = urlParts[productIdIndex];
    } else {
        // Thử lấy từ form action hoặc các nguồn khác
        const form = document.querySelector('form[action*="/products/"]');
        if (form) {
            const actionMatch = form.action.match(/\/products\/(\d+)/);
            if (actionMatch) {
                productId = actionMatch[1];
            }
        }
    }

    const skuInput = document.getElementById('sku-input');
    const barcodeInput = document.getElementById('barcode-input');
    const generateSkuBtn = document.getElementById('generate-sku-btn');
    const generateBarcodeBtn = document.getElementById('generate-barcode-btn');
    const storageSelect = document.querySelector('select[name="storage_id"]');
    const versionSelect = document.querySelector('select[name="version_id"]');
    const colorSelect = document.querySelector('select[name="color_id"]');

    // Hàm generate SKU
    function generateSku() {
        if (!productId) {
            alert('Không tìm thấy ID sản phẩm');
            return;
        }

        generateSkuBtn.disabled = true;
        generateSkuBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang tạo...';

        fetch(`/admin/products/${productId}/variants/generate-sku`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({
                version_id: versionSelect?.value || null,
                storage_id: storageSelect?.value || null,
                color_id: colorSelect?.value || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                skuInput.value = data.sku;
                skuInput.classList.add('border-success');
                setTimeout(() => skuInput.classList.remove('border-success'), 2000);
            } else {
                alert('Không thể tạo SKU. Vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tạo SKU.');
        })
        .finally(() => {
            generateSkuBtn.disabled = false;
            generateSkuBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Tạo';
        });
    }

    // Hàm generate Barcode
    function generateBarcode() {
        if (!productId) {
            alert('Không tìm thấy ID sản phẩm');
            return;
        }

        generateBarcodeBtn.disabled = true;
        generateBarcodeBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang tạo...';

        // Lấy variantId nếu đang edit
        const urlParts = window.location.pathname.split('/');
        const variantId = urlParts.includes('edit') ? urlParts[urlParts.length - 2] : null;

        fetch(`/admin/products/${productId}/variants/generate-barcode`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({
                variant_id: variantId || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                barcodeInput.value = data.barcode;
                barcodeInput.classList.add('border-success');
                setTimeout(() => barcodeInput.classList.remove('border-success'), 2000);
            } else {
                alert('Không thể tạo Barcode. Vui lòng thử lại.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tạo Barcode.');
        })
        .finally(() => {
            generateBarcodeBtn.disabled = false;
            generateBarcodeBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Tạo';
        });
    }

    // Event listeners
    if (generateSkuBtn) {
        generateSkuBtn.addEventListener('click', generateSku);
    }

    if (generateBarcodeBtn) {
        generateBarcodeBtn.addEventListener('click', generateBarcode);
    }

    // Tự động tạo SKU khi thay đổi storage, version, hoặc color (nếu SKU đang trống)
    function autoGenerateSkuIfEmpty() {
        if (!skuInput.value.trim()) {
            generateSku();
        }
    }

    if (storageSelect) {
        storageSelect.addEventListener('change', autoGenerateSkuIfEmpty);
    }
    if (versionSelect) {
        versionSelect.addEventListener('change', autoGenerateSkuIfEmpty);
    }
    if (colorSelect) {
        colorSelect.addEventListener('change', autoGenerateSkuIfEmpty);
    }
});
</script>

