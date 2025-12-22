@extends('electro.layout')

@section('title', 'Giỏ hàng')

@section('content')
    <!-- SECTION -->
    <div class="section">
        <div class="container">
            <div class="row">

                {{-- Cột trái: danh sách sản phẩm --}}
                <div class="col-md-8">
                    <div class="section-title">
                        <h3 class="title">Giỏ hàng của bạn</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div id="cart-alert"
                         style="display:none;"
                         class="alert"
                         data-server-error="{{ session('error') }}"
                         data-validation-errors='@json($errors->all())'></div>

                    @if($items->isEmpty())
                        <p>Giỏ hàng đang trống.</p>
                        <a href="{{ url('/client') }}" class="primary-btn">
                            Tiếp tục mua sắm
                        </a>
                    @else
                        {{-- Checkbox chọn tất cả --}}
                        <div style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
                            <label style="cursor: pointer; margin: 0;">
                                <input type="checkbox" id="select-all-checkbox" style="margin-right: 8px; cursor: pointer;">
                                <strong>Chọn tất cả sản phẩm</strong>
                            </label>
                        </div>

                        <div class="shopping-cart">
                            @foreach($items as $item)
                                @php
                                    $variant = $item->variant;
                                    $product = $variant ? $variant->product : null;
                                    $price = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;

                                    // Lấy ảnh từ variant hoặc product
                                    $image = null;
                                    if ($variant && $variant->image) {
                                        $image = preg_match('/^https?:\/\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image);
                                    } elseif ($product && $product->image) {
                                        $image = preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image);
                                    }

                                    // Tạo tên sản phẩm với thông tin variant
                                    $productName = $product ? $product->name : 'Sản phẩm';
                                    $variantInfo = [];
                                    if ($variant) {
                                        if ($variant->storage) $variantInfo[] = $variant->storage->storage;
                                        if ($variant->version) $variantInfo[] = $variant->version->name;
                                        if ($variant->color) $variantInfo[] = $variant->color->name;
                                    }
                                    if (!empty($variantInfo)) {
                                        $productName .= ' (' . implode(', ', $variantInfo) . ')';
                                    }
                                @endphp

                                <div class="cart-item" data-item-id="{{ $item->id }}" data-price="{{ $price }}" data-quantity="{{ $item->quantity }}" style="display: flex; align-items: flex-start; gap: 15px; border-bottom: 1px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 15px; position: relative;">
                                    {{-- Checkbox bên trái --}}
                                    <div style="padding-top: 40px;">
                                        <input type="checkbox" 
                                               class="item-checkbox" 
                                               data-item-id="{{ $item->id }}"
                                               data-price="{{ $price }}"
                                               data-quantity="{{ $item->quantity }}"
                                               checked
                                               style="width: 18px; height: 18px; cursor: pointer; margin: 0;">
                                    </div>

                                    {{-- Product widget --}}
                                    <div class="product-widget" style="flex: 1; border: none; padding: 0; margin: 0; display: flex; gap: 15px;">
                                        <div class="product-img">
                                            @if($image)
                                                <img src="{{ $image }}" alt="{{ $productName }}">
                                            @else
                                                <img src="{{ asset('electro/img/product01.png') }}" alt="">
                                            @endif
                                        </div>
                                        <div class="product-body" style="flex: 1;">
                                            <h3 class="product-name">
                                                <a href="{{ $product ? route('client.product.show', $product->slug) : '#' }}">
                                                    {{ $productName }}
                                                </a>
                                            </h3>
                                            @if($variant && $variant->sku)
                                                <p class="text-muted small" style="margin: 5px 0;">SKU: {{ $variant->sku }}</p>
                                            @endif

                                            <h4 class="product-price">
                                                <span class="qty">{{ $item->quantity }}x</span>
                                                {{ number_format($price, 0, ',', '.') }} ₫
                                            </h4>

                                            {{-- Form cập nhật số lượng --}}
                                            <form action="{{ route('cart.update') }}" method="POST" class="form-inline cart-update-form" style="margin-top: 5px;">
                                                @csrf
                                                <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                                @php
                                                    $stock = $variant->stock ?? 0;
                                                    $max = max(1, min($stock, 10)); // tối đa 10/sp
                                                @endphp
                                                <input type="number"
                                                       name="quantity"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       data-max="{{ $max }}"
                                                       data-cart-item-id="{{ $item->id }}"
                                                       class="input quantity-input"
                                                       style="width: 80px; display:inline-block; margin-right:5px;"
                                                       {{ $stock <= 0 ? 'disabled' : '' }}>
                                                <button type="submit" class="primary-btn update-btn" style="display:none;">
                                                    Cập nhật
                                                </button>
                                                <span class="update-status" style="margin-left: 10px; display:none; color: #28a745;">✓ Đã cập nhật</span>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Nút xóa bên phải --}}
                                    <div style="padding-top: 10px;">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <button type="submit" 
                                                    class="delete" 
                                                    onclick="return confirm('Xóa sản phẩm này?')"
                                                    style="position: static; background: #f44336; color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                                                <i class="fa fa-close" style="font-size: 16px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Nút xóa toàn bộ --}}
                        <form action="{{ route('cart.clear') }}" method="POST" style="margin-top: 10px;">
                            @csrf
                            <button type="submit" class="primary-btn cta-btn"
                                    style="background: #fff; color:#D10024; border:1px solid #D10024;"
                                    onclick="return confirm('Xóa toàn bộ giỏ hàng?')">
                                Xóa toàn bộ giỏ
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Cột phải: tóm tắt đơn hàng --}}
                <div class="col-md-4">
                    <div class="order-details">
                        <div class="section-title text-center">
                            <h3 class="title"> Giỏ hàng</h3>
                            <p class="text-muted" id="selected-count" style="font-size: 14px; margin-top: 5px;">
                                Đã chọn <span id="selected-item-count">{{ $items->count() }}</span>/{{ $items->count() }} sản phẩm
                            </p>
                        </div>

                        <div class="order-summary">
                            <div class="order-col">
                                <div><strong>SẢN PHẨM</strong></div>
                                <div><strong>TỔNG</strong></div>
                            </div>

                            <div class="order-products" id="selected-products-list">
                                @foreach($items as $item)
                                    @php
                                        $variant = $item->variant;
                                        $product = $variant ? $variant->product : null;
                                        $price = $variant ? ($variant->price_sale ?? $variant->price ?? 0) : 0;
                                        $productName = $product ? $product->name : 'Sản phẩm';

                                        // Thêm thông tin variant vào tên
                                        $variantInfo = [];
                                        if ($variant) {
                                            if ($variant->storage) $variantInfo[] = $variant->storage->storage;
                                            if ($variant->version) $variantInfo[] = $variant->version->name;
                                            if ($variant->color) $variantInfo[] = $variant->color->name;
                                        }
                                        if (!empty($variantInfo)) {
                                            $productName .= ' (' . implode(', ', $variantInfo) . ')';
                                        }
                                    @endphp
                                    <div class="order-col summary-item" data-item-id="{{ $item->id }}">
                                        <div>{{ $item->quantity }}x {{ $productName }}</div>
                                        <div>{{ number_format($price * $item->quantity, 0, ',', '.') }} ₫</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="order-col">
                                <div><strong>TỔNG CỘNG</strong></div>
                                <div><strong class="order-total" id="cart-total">
                                        {{ number_format($total, 0, ',', '.') }} ₫
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('client.checkout') }}" class="primary-btn order-submit" id="checkout-btn">
                            Tiến hành thanh toán
                        </a>

                        <a href="{{ url('/client') }}" class="primary-btn cta-btn" style="margin-top:10px;">
                            ← Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- /SECTION -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('cart-alert');
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const cartTotal = document.getElementById('cart-total');
    const selectedCountSpan = document.getElementById('selected-item-count');
    const selectedProductsList = document.getElementById('selected-products-list');
    const checkoutBtn = document.getElementById('checkout-btn');

    function showCartAlert(message, type = 'danger') {
        if (!alertBox) return;
        alertBox.textContent = message;
        alertBox.className = 'alert alert-' + type;
        alertBox.style.display = 'block';
        // Auto hide after 4s
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }

    // Calculate total from selected items
    function updateTotal() {
        let total = 0;
        let selectedCount = 0;
        const selectedItems = [];

        itemCheckboxes.forEach(checkbox => {
            const summaryItem = document.querySelector(`.summary-item[data-item-id="${checkbox.dataset.itemId}"]`);
            
            if (checkbox.checked) {
                const price = parseFloat(checkbox.dataset.price) || 0;
                const quantity = parseInt(checkbox.dataset.quantity) || 0;
                total += price * quantity;
                selectedCount++;
                selectedItems.push(checkbox.dataset.itemId);
                
                // Show in summary
                if (summaryItem) {
                    summaryItem.style.display = '';
                }
            } else {
                // Hide from summary
                if (summaryItem) {
                    summaryItem.style.display = 'none';
                }
            }
        });

        // Update total display
        if (cartTotal) {
            cartTotal.textContent = total.toLocaleString('vi-VN') + ' ₫';
        }

        // Update selected count
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedCount;
        }

        // Save selected items to sessionStorage for checkout
        sessionStorage.setItem('selectedCartItems', JSON.stringify(selectedItems));

        // Update "select all" checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === itemCheckboxes.length && selectedCount > 0;
        }

        return selectedCount;
    }

    // Handle item checkbox change
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateTotal();
        });
    });

    // Handle "select all" checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateTotal();
        });
    }

    // Validate before checkout
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            const selectedCount = updateTotal();
            if (selectedCount === 0) {
                e.preventDefault();
                showCartAlert('Vui lòng chọn ít nhất một sản phẩm để thanh toán!', 'warning');
                return;
            }
            
            // Get selected item IDs
            const selectedItems = [];
            itemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedItems.push(checkbox.dataset.itemId);
                }
            });
            
            // Append to checkout URL
            e.preventDefault();
            const baseUrl = this.href;
            const separator = baseUrl.includes('?') ? '&' : '?';
            window.location.href = baseUrl + separator + 'selected_items=' + selectedItems.join(',');
        });
    }

    // Auto update quantity on input change
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.dataset.max) || 0;
            const val = parseInt(this.value) || 1;
            
            // Validate quantity
            if (val < 1) {
                this.value = 1;
                return;
            }
            if (max > 0 && val > max) {
                showCartAlert('Vượt quá số lượng tối đa hàng tồn kho (' + max + ').', 'warning');
                this.value = max;
                return;
            }

            // Auto submit
            const form = this.closest('.cart-update-form');
            const cartItemId = this.dataset.cartItemId;
            const updateBtn = form.querySelector('.update-btn');
            const updateStatus = form.querySelector('.update-status');

            // Show loading state
            if (updateStatus) {
                updateStatus.textContent = '⟳ Đang cập nhật...';
                updateStatus.style.display = 'inline';
                updateStatus.style.color = '#ffc107';
            }

            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    cart_item_id: cartItemId,
                    quantity: val
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || data.message || 'Lỗi cập nhật');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (updateStatus) {
                    updateStatus.textContent = '✓ Đã cập nhật';
                    updateStatus.style.color = '#28a745';
                    setTimeout(() => {
                        updateStatus.style.display = 'none';
                    }, 2000);
                }
                
                // Update data attributes for recalculation
                const checkbox = document.querySelector(`.item-checkbox[data-item-id="${cartItemId}"]`);
                if (checkbox) {
                    checkbox.dataset.quantity = val;
                }
                
                // Recalculate total
                updateTotal();
                
                // Reload page after a delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showCartAlert(error.message || 'Lỗi khi cập nhật giỏ hàng', 'danger');
                if (updateStatus) {
                    updateStatus.textContent = '✗ Lỗi';
                    updateStatus.style.color = '#dc3545';
                    setTimeout(() => {
                        updateStatus.style.display = 'none';
                    }, 2000);
                }
            });
        });
    });

    // Check quantity against max before submit (fallback)
    const forms = document.querySelectorAll('.cart-update-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const qtyInput = form.querySelector('input[name="quantity"]');
            const max = parseInt(qtyInput?.dataset.max) || 0;
            const val = parseInt(qtyInput?.value) || 0;
            if (max > 0 && val > max) {
                e.preventDefault();
                showCartAlert('Vượt quá số lượng tối đa hàng tồn kho.', 'warning');
                qtyInput.value = max;
                qtyInput.focus();
            }
        });
    });

    // Hiển thị lỗi từ server (nếu có) từ data attribute
    const serverError = alertBox ? alertBox.getAttribute('data-server-error') : '';
    if (serverError) {
        showCartAlert(serverError, 'danger');
    }

    const validationErrorsRaw = alertBox ? alertBox.getAttribute('data-validation-errors') : '[]';
    try {
        const validationErrors = JSON.parse(validationErrorsRaw || '[]');
        if (Array.isArray(validationErrors)) {
            validationErrors.forEach(err => showCartAlert(err, 'danger'));
        }
    } catch (e) {
        console.error('Cannot parse validation errors', e);
    }

    // Initialize total calculation on page load
    updateTotal();
});
</script>
@endpush
@endsection
