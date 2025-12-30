<?php


namespace App\Http\Controllers\Client;


use App\Http\Controllers\Concerns\HandlesActiveCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class CheckoutController extends Controller
{
    use HandlesActiveCart;


    public function show()
    {
        // Ngăn admin đặt hàng
        if (auth()->check() && auth()->user()->role_id == 1) {
            return redirect()->route('client.index')->with('error', 'Admin không thể đặt hàng.');
        }


        $cart = $this->getOrCreateActiveCart();
        $allItems = $cart->items()->with(['variant.product', 'variant'])->get();


        if ($allItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Lọc items được chọn từ request (sent via query parameter from cart page)
        // Lọc items được chọn từ request (sent via query parameter from cart page)
        $selectedItemIds = request('selected_items');
        
        if ($selectedItemIds) {
            // Case 1: Có request params (từ giỏ hàng sang) -> Cập nhật session
            // Convert comma-separated string to array
            $selectedItemIds = is_array($selectedItemIds) ? $selectedItemIds : explode(',', $selectedItemIds);
            $selectedItemIds = array_filter(array_map('intval', $selectedItemIds));
            
            // Save to session for use in store() and subsequent requests
            session(['selected_cart_items' => $selectedItemIds]);
        } else {
            // Case 2: Không có request params (redirect back, validated fail, f5) -> Lấy từ session
            $selectedItemIds = session('selected_cart_items');
        }

        if ($selectedItemIds && is_array($selectedItemIds)) {
            // Filter items based on IDs
            $items = $allItems->filter(function($item) use ($selectedItemIds) {
                return in_array($item->id, $selectedItemIds);
            });
        } else {
            // Fallback: use all items only if no selection in request OR session
            // (Only happens if user goes directly to checkout without selecting anything ever)
            $items = $allItems;
        }


        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }


        // Giới hạn tổng số lượng sản phẩm trong đơn hàng
        $totalQuantity = $items->sum('quantity');
        if ($totalQuantity > 10) {
            return redirect()->route('cart.index')
                ->with('error', 'Bạn chỉ được đặt tối đa 10 sản phẩm cho mỗi đơn hàng.');
        }


        try {
            $this->guardItemsHaveVariant($items);
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }


        // Lấy coupon code từ request hoặc session
        $couponCode = request('coupon_code') ?? session('checkout_coupon_code');
        $coupon = null;
        $userId = auth()->id();
        if ($couponCode) {
            // Tính subtotal để validate min_order_value
            $subtotal = $items->sum(function ($item) {
                $variant = $item->variant;
                return $this->getVariantPrice($variant, $item) * $item->quantity;
            });
           
            // Lấy product IDs từ giỏ hàng để validate product-level coupons
            $productIds = $items->pluck('variant.product_id')->unique()->toArray();
            $coupon = Coupon::validateCode($couponCode, $userId, $productIds, $subtotal);
            if ($coupon) {
                session(['checkout_coupon_code' => $couponCode]);
            } else {
                session()->forget('checkout_coupon_code');
            }
        }


        $summary = $this->buildSummary($items, $coupon);
        $paymentMethods = config('checkout.payment_methods', []);
        $user = auth()->user();


        // Lấy danh sách mã khuyến mãi mà user có thể sử dụng
        $availableCoupons = $this->getAvailableCoupons($userId);


        $prefill = [
            'full_name' => $user?->name,
            'email'     => $user?->email,
            'phone'     => $user?->phone,
            'address'   => $user?->address,
            'city'      => null,
            'district'  => null,
            'ward'      => null,
            'notes'     => null,
            'coupon_code' => $couponCode,
        ];


        return view('electro.checkout', compact('cart', 'items', 'summary', 'paymentMethods', 'prefill', 'coupon', 'availableCoupons'));
    }


    public function store(CheckoutRequest $request)
    {
        // Ngăn admin đặt hàng
        if (auth()->check() && auth()->user()->role_id == 1) {
            return redirect()->route('client.index')->with('error', 'Admin không thể đặt hàng.');
        }


        $cart = $this->getOrCreateActiveCart();
        $userId = $this->resolveCartUserId();
       
        // Đảm bảo cart thuộc về user hiện tại
        if ((int)$cart->user_id !== (int)$userId) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng không thuộc về bạn. Vui lòng thử lại.');
        }
       
        // Đảm bảo chỉ lấy items từ cart của user hiện tại
        // Load variant với tất cả các trường cần thiết, đặc biệt là stock
        $allItems = $cart->items()->with(['variant.product', 'variant'])->get();


        if ($allItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Lọc items được chọn
        // Ưu tiên lấy từ request (hidden field) để đảm bảo chính xác những gì user nhìn thấy
        $selectedItemIds = request('selected_items');
        
        if (!$selectedItemIds) {
            // Fallback: Lấy từ session (trường hợp redirect back hoặc logic cũ)
            $selectedItemIds = session('selected_cart_items');
        }

        if ($selectedItemIds) {
            // Nếu là string từ hidden input (comma separated)
            if (!is_array($selectedItemIds)) {
                $selectedItemIds = explode(',', $selectedItemIds);
            }
            // Filter empty values and validate int
            $selectedItemIds = array_filter(array_map('intval', $selectedItemIds));
            
            if (!empty($selectedItemIds)) {
                 $items = $allItems->filter(function($item) use ($selectedItemIds) {
                    return in_array($item->id, $selectedItemIds);
                });
            } else {
                 // Trường hợp selected_items rỗng (ví dụ value="") -> Không chọn sản phẩm nào
                 return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
            }
        } else {
            // Fallback: use all items if no selection found anywhere (old behavior)
            $items = $allItems;
        }


        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }
       
        // Debug: Log chi tiết để kiểm tra
        \Log::info('Checkout store - Cart and Items', [
            'cart_id' => $cart->id,
            'cart_user_id' => $cart->user_id,
            'current_user_id' => $userId,
            'raw_items_count' => $allItems->count(),
            'items_count' => $items->count(),
            'items' => $items->map(function ($item) {
                return [
                    'cart_item_id' => $item->id,
                    'cart_id' => $item->cart_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_id' => $item->variant->product_id ?? null,
                    'product_name' => $item->variant->product->name ?? 'N/A',
                    'quantity' => $item->quantity,
                ];
            })->toArray()
        ]);


        try {
            $this->guardItemsHaveVariant($items);
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }


        $data = $request->validated();


        // Validate và lấy coupon (sẽ validate lại trong transaction với lock)
        $coupon = null;
        $couponCode = $data['coupon_code'] ?? null;
        $userId = auth()->id();
       
        // Tính subtotal sơ bộ để validate
        $preliminarySubtotal = $items->sum(function ($item) {
            $variant = $item->variant;
            return $this->getVariantPrice($variant, $item) * $item->quantity;
        });
       
        if ($couponCode) {
            // Validate mã khuyến mãi chi tiết, trả về đúng lý do lỗi nếu có
            [$coupon, $couponError] = $this->validateCouponWithReason(
                $couponCode,
                $userId,
                $items,
                $preliminarySubtotal
            );

            if (!$coupon) {
                return redirect()
                    ->route('client.checkout', ['coupon_code' => $couponCode])
                    ->withInput()
                    ->with('error', $couponError ?? 'Mã khuyến mãi không hợp lệ.');
            }
        }


        $summary = $this->buildSummary($items, $coupon);

        // Nếu chọn VNPAY, không tạo Order ngay, chỉ lưu thông tin vào session
        if ($data['payment_method'] === 'vnpay') {
            // Tạo transaction ID duy nhất để gửi cho VNPAY
            $transactionId = 'VNPAY_' . time() . '_' . uniqid();
            
            // Lưu thông tin đơn hàng vào session để tạo Order sau khi thanh toán thành công
            session(['pending_vnpay_order' => [
                'transaction_id' => $transactionId,
                'cart_id' => $cart->id,
                'user_id' => $userId,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $couponCode,
                'summary' => $summary,
                'shipping_data' => [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'],
                    'city' => $data['city'] ?? null,
                    'district' => $data['district'] ?? null,
                    'ward' => $data['ward'] ?? null,
                    'address' => $data['address'],
                    'notes' => $data['notes'] ?? null,
                ],
                'items_data' => $items->map(function ($item) {
                    $variant = $item->variant;
                    $product = $variant->product ?? $item->product;
                    return [
                        'cart_item_id' => $item->id,
                        'product_id' => $product?->id,
                        'product_variant_id' => $variant->id,
                        'product_name' => $product->name ?? 'Sản phẩm',
                        'product_image' => $product->image ?? null,
                        'quantity' => $item->quantity,
                        'unit_price' => $this->getVariantPrice($variant, $item),
                    ];
                })->toArray(),
                'selected_item_ids' => session('selected_cart_items'),
            ]]);
            
            // Tạo URL thanh toán VNPAY với transaction ID
            $paymentUrl = $this->buildVnpayUrlForPending($transactionId, $summary['total']);
            return redirect()->away($paymentUrl);
        }

        // Các phương thức khác (COD): Tạo Order ngay như bình thường
        try {
            $order = DB::transaction(function () use ($cart, $items, $summary, $data, $coupon, $couponCode, $userId) {
                // QUAN TRỌNG: Lock cart và kiểm tra xem đã có order nào được tạo từ cart này chưa
                // Điều này ngăn chặn race condition khi nhiều requests cùng checkout với cùng cart
                $lockedCart = Cart::lockForUpdate()->find($cart->id);
                if (!$lockedCart) {
                    throw new \Exception('Giỏ hàng không tồn tại. Vui lòng thử lại.');
                }
               
                // Kiểm tra cart đã được converted chưa
                if ($lockedCart->status === 'converted') {
                    \Log::warning('Attempt to create order from already converted cart', [
                        'cart_id' => $cart->id,
                        'cart_status' => $lockedCart->status,
                        'user_id' => $userId,
                    ]);
                    throw new \Exception('Giỏ hàng này đã được sử dụng để tạo đơn hàng. Vui lòng sử dụng giỏ hàng mới.');
                }
               
                // Kiểm tra xem đã có order nào được tạo từ cart này chưa (double check)
                $existingOrder = Order::where('cart_id', $lockedCart->id)->first();
                if ($existingOrder) {
                    \Log::warning('Order already exists for cart', [
                        'cart_id' => $lockedCart->id,
                        'existing_order_id' => $existingOrder->id,
                        'user_id' => $userId,
                    ]);
                    throw new \Exception('Đơn hàng đã được tạo từ giỏ hàng này. Vui lòng kiểm tra đơn hàng của bạn.');
                }
               
                // Giới hạn tổng số lượng sản phẩm trong đơn hàng
                $totalQuantity = $items->sum('quantity');
                if ($totalQuantity > 10) {
                    throw new \Exception('Bạn chỉ được đặt tối đa 10 sản phẩm cho mỗi đơn hàng.');
                }


                // Nếu có coupon, lock và validate lại trong transaction (tránh race condition)
                $validatedCoupon = null;
                if ($coupon && $couponCode) {
                    // Lock coupon row để tránh race condition
                    $lockedCoupon = Coupon::lockForUpdate()->find($coupon->id);
                   
                    if (!$lockedCoupon) {
                        throw new \Exception('Mã khuyến mãi không tồn tại.');
                    }
                   
                    // Validate lại với dữ liệu mới nhất (sau khi lock)
                    // Kiểm tra lại các điều kiện quan trọng
                    if (!$lockedCoupon->isValid()) {
                        throw new \Exception('Mã khuyến mãi đã hết hạn hoặc chưa đến thời gian sử dụng.');
                    }
                   
                    if (!$lockedCoupon->canBeUsedBy($userId)) {
                        throw new \Exception('Bạn không có quyền sử dụng mã khuyến mãi này.');
                    }
                   
                    if ($lockedCoupon->hasReachedUsageLimit()) {
                        throw new \Exception('Mã khuyến mãi đã hết lượt sử dụng.');
                    }
                   
                    if ($lockedCoupon->hasReachedUserUsageLimit($userId)) {
                        throw new \Exception('Bạn đã sử dụng hết lượt cho mã khuyến mãi này.');
                    }
                   
                    $finalSubtotal = $summary['subtotal'];
                    if (!$lockedCoupon->canBeAppliedToSubtotal($finalSubtotal)) {
                        throw new \Exception('Đơn hàng chưa đạt giá trị tối thiểu để sử dụng mã khuyến mãi này.');
                    }
                   
                    // Validate product-level coupon
                    if ($lockedCoupon->isForProduct()) {
                        $productIds = $items->pluck('variant.product_id')->unique()->toArray();
                        $applicableProducts = $lockedCoupon->products()->whereIn('product_id', $productIds)->count();
                        if ($applicableProducts === 0) {
                            throw new \Exception('Mã khuyến mãi không áp dụng cho sản phẩm trong giỏ hàng.');
                        }
                    }
                   
                    $validatedCoupon = $lockedCoupon;
                }


                $order = Order::create([
                    'cart_id'             => $lockedCart->id,
                    'user_id'             => $lockedCart->user_id,
                    'coupon_id'           => $validatedCoupon?->id,
                    'subtotal'            => $summary['subtotal'],
                    'shipping_fee'        => $summary['shipping_fee'],
                    'discount_amount'     => $summary['discount'],
                    'total'               => $summary['total'],
                    'payment_method'      => $data['payment_method'],
                    'payment_status'      => 0, // 0 = chưa thanh toán, 1 = đã thanh toán
                    'status'              => 'cho_xac_nhan', // Trạng thái đầu tiên: Chờ xác nhận
                    'shipping_full_name'  => $data['full_name'],
                    'shipping_email'      => $data['email'] ?? null,
                    'shipping_phone'      => $data['phone'],
                    'shipping_city'       => $data['city'] ?? null,
                    'shipping_district'   => $data['district'] ?? null,
                    'shipping_ward'       => $data['ward'] ?? null,
                    'shipping_address'    => $data['address'],
                    'notes'               => $data['notes'] ?? null,
                ]);


                // Items đã được group theo product_variant_id ở trên, giờ chỉ cần loop qua
                // Mỗi item trong $items đã là unique và quantity đã được tổng hợp
                // Đảm bảo chỉ tạo order items từ items trong cart của user hiện tại
               
                // Log trước khi tạo order items
                \Log::info('Creating order items', [
                    'order_id' => $order->id,
                    'cart_id' => $lockedCart->id,
                    'user_id' => $userId,
                    'items_count' => $items->count(),
                    'items_detail' => $items->map(function ($item) {
                        return [
                            'cart_item_id' => $item->id,
                            'cart_id' => $item->cart_id,
                            'product_variant_id' => $item->product_variant_id,
                            'product_id' => $item->variant->product_id ?? null,
                            'product_name' => $item->variant->product->name ?? 'N/A',
                            'quantity' => $item->quantity,
                        ];
                    })->toArray()
                ]);
               
                $createdOrderItems = [];
                foreach ($items as $item) {
                    // Triple check: đảm bảo item thuộc về cart này
                    if ($item->cart_id !== $lockedCart->id) {
                        \Log::warning('Cart item does not belong to cart - SKIPPING', [
                            'item_id' => $item->id,
                            'item_cart_id' => $item->cart_id,
                            'cart_id' => $lockedCart->id,
                            'user_id' => $userId,
                            'product_name' => $item->variant->product->name ?? 'N/A',
                        ]);
                        continue; // Bỏ qua item không thuộc về cart này
                    }
                   
                    $variant = $item->variant;
                   
                    if (!$variant) {
                        throw new \Exception("Sản phẩm trong giỏ hàng chưa được chọn biến thể. Vui lòng thử lại sau khi cập nhật giỏ hàng.");
                    }


                    $product = $variant->product ?? $item->product;


                    // Sử dụng giá sale nếu có, nếu không thì dùng giá thường
                    $unitPrice = $this->getVariantPrice($variant, $item);


                    // Quantity đã được tổng hợp khi group ở trên
                    $quantity = $item->quantity;

                    $orderItem = OrderItem::create([
                        'order_id'             => $order->id,
                        'product_id'           => $product?->id,
                        'product_variant_id'   => $variant->id, // Lưu variant ID để hoàn stock chính xác
                        'product_name'         => $product->name ?? 'Sản phẩm',
                        'product_image'        => $product->image ?? null,
                        'quantity'             => $quantity,
                        'unit_price'           => $unitPrice,
                        'total_price'          => $unitPrice * $quantity,
                    ]);
                   
                    $createdOrderItems[] = [
                        'order_item_id' => $orderItem->id,
                        'product_name' => $orderItem->product_name,
                        'quantity' => $orderItem->quantity,
                        'total_price' => $orderItem->total_price,
                    ];
                   
                    \Log::info('Order item created', [
                        'order_item_id' => $orderItem->id,
                        'order_id' => $order->id,
                        'product_name' => $orderItem->product_name,
                        'quantity' => $orderItem->quantity,
                        'total_price' => $orderItem->total_price,
                    ]);


                    // Trừ số lượng sản phẩm (stock) khi đặt hàng thành công
                    if ($variant) {
                        // Lấy variant mới nhất từ database để tránh race condition
                        $variant = ProductVariant::lockForUpdate()->find($variant->id);


                        if (!$variant) {
                            throw new \Exception("Không tìm thấy biến thể sản phẩm.");
                        }


                        // Kiểm tra stock có đủ không (chỉ kiểm tra nếu stock không null)
                        if ($variant->stock === null) {
                            // Nếu stock là null, không quản lý stock cho variant này
                            continue;
                        }


                        if ($variant->stock < $quantity) {
                            // Nếu không đủ hàng, rollback transaction
                            throw new \Exception("Sản phẩm {$product->name} không đủ số lượng trong kho. Số lượng còn lại: {$variant->stock}");
                        }


                        // Tính toán stock mới trước khi trừ
                        $newStock = $variant->stock - $quantity;


                        // Trừ stock và tăng sold bằng cách update trực tiếp
                        $variant->update([
                            'stock' => $newStock,
                            'sold' => ($variant->sold ?? 0) + $quantity,
                        ]);


                        // Cập nhật status nếu hết hàng
                        if ($newStock <= 0) {
                            $variant->update(['status' => \App\Models\ProductVariant::STATUS_OUT_OF_STOCK]);
                        }
                    }
                }


                // Chỉ xóa các items đã được chọn và tạo order
                // Giữ lại các items không được chọn trong giỏ hàng
                $selectedItemIds = session('selected_cart_items');
                if ($selectedItemIds && is_array($selectedItemIds)) {
                    // Chỉ xóa items đã chọn
                    CartItem::whereIn('id', $selectedItemIds)
                        ->where('cart_id', $cart->id)
                        ->delete();
                   
                    // Kiểm tra xem còn items nào trong giỏ không
                    $remainingItems = $cart->items()->count();
                    if ($remainingItems === 0) {
                        // Nếu không còn items, đánh dấu cart là converted
                        $cart->update(['status' => 'converted']);
                    }
                    // Nếu còn items, giữ nguyên cart status là 'active'
                } else {
                    // Fallback: xóa tất cả items (trường hợp checkout tất cả)
                    $cart->update(['status' => 'converted']);
                    $cart->items()->delete();
                }
               
                // Xóa session selected items
                session()->forget('selected_cart_items');


                // Record coupon usage sau khi tạo order thành công
                if ($validatedCoupon) {
                    $validatedCoupon->recordUsage($userId, $order->id);
                }


                // Xóa coupon code khỏi session sau khi đặt hàng thành công
                session()->forget('checkout_coupon_code');


                return $order;
            });


            // Đảm bảo order được tạo thành công
            if (!$order || !$order->id) {
                return redirect()
                    ->route('client.checkout')
                    ->withInput()
                    ->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.');
            }

            // Các phương thức khác (ví dụ: COD) -> gửi email xác nhận ngay sau khi tạo đơn
            try {
                $order->user->notify(new \App\Notifications\OrderConfirmationNotification($order));
            } catch (\Exception $e) {
                // Log lỗi nhưng không làm gián đoạn flow đặt hàng
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }


            return redirect()
                ->route('client.checkout.success', ['order' => $order->id])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Checkout error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
           
            return redirect()
                ->route('client.checkout')
                ->withInput()
                ->with('error', $e->getMessage() ?: 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }


    public function success($order)
    {
        // Nếu là route model binding, $order sẽ là Order object
        // Nếu không, sẽ là ID và cần tìm Order
        if (is_numeric($order)) {
            $order = Order::findOrFail($order);
        } elseif (!$order instanceof Order) {
            abort(404);
        }


        $userId = $this->resolveCartUserId();


        if ((int) $order->user_id !== $userId) {
            abort(404);
        }


        $order->load('items', 'coupon');


        return view('electro.checkout-success', [
            'order'           => $order,
            'paymentMethods'  => config('checkout.payment_methods', []),
        ]);
    }


    protected function buildSummary(Collection $items, ?Coupon $coupon = null): array
    {
        $shippingFee = (float) config('checkout.shipping_fee', 0);
        $discount = 0;
        $subtotal = 0;


        // Tính discount dựa trên loại coupon
        if ($coupon && $coupon->isValid()) {
            if ($coupon->isForOrder()) {
                // Coupon cho đơn hàng: tính subtotal toàn bộ, sau đó tính discount
                $subtotal = $items->sum(function ($item) {
                    $variant = $item->variant;
                    return $this->getVariantPrice($variant, $item) * $item->quantity;
                });
               
                // Tính discount cho toàn bộ đơn hàng
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                // Coupon cho sản phẩm: tính discount cho từng sản phẩm được áp dụng
                $subtotal = 0;
                $eligibleSubtotal = 0; // Tổng giá trị các sản phẩm được áp dụng coupon
               
                foreach ($items as $item) {
                    $variant = $item->variant;
                    $productPrice = $this->getVariantPrice($variant, $item);
                    $itemSubtotal = $productPrice * $item->quantity;
                    $subtotal += $itemSubtotal;
                   
                    // Kiểm tra coupon có áp dụng cho sản phẩm này không
                    if ($coupon->appliesToProduct($variant->product_id)) {
                        // Tính tổng giá trị sản phẩm được áp dụng (để kiểm tra min_order_value nếu cần)
                        $eligibleSubtotal += $itemSubtotal;
                       
                        // Tính discount cho từng sản phẩm
                        $productDiscount = $coupon->calculateProductDiscount($productPrice);
                        $discount += $productDiscount * $item->quantity; // Nhân với số lượng
                    }
                }
               
                // Nếu có min_order_value, kiểm tra với eligible_subtotal (chỉ phần sản phẩm được áp dụng)
                // Lưu ý: Logic này có thể tùy chỉnh tùy business requirement
                // Ở đây ta vẫn dùng tổng subtotal để check min_order_value (theo yêu cầu ban đầu)
            }
        } else {
            // Không có coupon, chỉ tính subtotal
            $subtotal = $items->sum(function ($item) {
                $variant = $item->variant;
                return $this->getVariantPrice($variant, $item) * $item->quantity;
            });
        }

        $total = max($subtotal - $discount + $shippingFee, 0);

        return [
            'subtotal'     => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount'     => $discount,
            'total'        => $total,
        ];
    }

    /**
     * Validate coupon chi tiết và trả về kèm lý do lỗi (nếu có)
     *
     * @param string $code
     * @param int|null $userId
     * @param \Illuminate\Support\Collection $items
     * @param float $subtotal
     * @return array [Coupon|null, string|null]  // [coupon hợp lệ hoặc null, thông báo lỗi hoặc null]
     */
    protected function validateCouponWithReason(string $code, ?int $userId, Collection $items, float $subtotal): array
    {
        $normalizedCode = strtoupper(trim($code));
        $coupon = Coupon::where('code', $normalizedCode)->first();

        if (!$coupon) {
            return [null, 'Mã khuyến mãi không tồn tại.'];
        }

        // 1. Kiểm tra thời gian hiệu lực
        if (!$coupon->hasStarted()) {
            return [null, 'Mã khuyến mãi này chưa đến thời gian áp dụng.'];
        }

        if ($coupon->isExpired()) {
            return [null, 'Mã khuyến mãi này đã hết hạn.'];
        }

        // 2. Kiểm tra quyền sử dụng theo loại mã
        if (($coupon->type ?? 'public') === 'private') {
            if (!$userId) {
                return [null, 'Bạn cần đăng nhập để sử dụng mã khuyến mãi này.'];
            }

            $canUse = $coupon->users()
                ->where('user_id', $userId)
                ->exists();

            if (!$canUse) {
                return [null, 'Mã khuyến mãi này chỉ dành cho một số khách hàng nhất định, bạn không có quyền sử dụng.'];
            }
        }

        // 3. Kiểm tra giới hạn số lần sử dụng
        if ($coupon->hasReachedUsageLimit()) {
            return [null, 'Mã khuyến mãi này đã được sử dụng hết số lượt cho phép.'];
        }

        if ($coupon->hasReachedUserUsageLimit($userId)) {
            return [null, 'Bạn đã sử dụng hết số lần cho phép đối với mã khuyến mãi này.'];
        }

        // 4. Kiểm tra giá trị đơn hàng tối thiểu
        if (!$coupon->canBeAppliedToSubtotal($subtotal)) {
            $minOrder = $coupon->min_order_value ?? 0;
            $minOrderText = number_format($minOrder, 0, ',', '.');
            return [null, "Đơn hàng chưa đạt giá trị tối thiểu {$minOrderText} ₫ để sử dụng mã khuyến mãi này."];
        }

        // 5. Kiểm tra phạm vi áp dụng (sản phẩm)
        if ($coupon->isForProduct()) {
            $productIds = $items->pluck('variant.product_id')->unique()->toArray();
            $applicableProducts = $coupon->products()
                ->whereIn('product_id', $productIds)
                ->count();

            if ($applicableProducts === 0) {
                return [null, 'Mã khuyến mãi này không áp dụng cho bất kỳ sản phẩm nào trong giỏ hàng của bạn.'];
            }
        }

        // Nếu qua hết các bước trên thì coi như hợp lệ
        return [$coupon, null];
    }


    /**
     * Validate coupon code via AJAX
     */
    public function validateCoupon()
    {
        $code = request('code');
        $userId = auth()->id();


        // Lấy giỏ hàng để tính summary
        $cart = $this->getOrCreateActiveCart();
        $items = $cart->items()->with(['variant.product', 'variant'])->get();


        try {
            $this->guardItemsHaveVariant($items);
        } catch (\RuntimeException $e) {
            return response()->json([
                'valid' => false,
                'message' => $e->getMessage(),
            ], 422);
        }


        // Nếu không có code hoặc code rỗng, trả về summary không có coupon
        if (!$code || trim($code) === '') {
            $summary = $this->buildSummary($items, null);
            return response()->json([
                'valid' => false,
                'message' => 'Đã bỏ chọn mã khuyến mãi',
                'summary' => $summary,
            ]);
        }


        // Tính subtotal để validate min_order_value
        $subtotal = $items->sum(function ($item) {
            $variant = $item->variant;
            return $this->getVariantPrice($variant, $item) * $item->quantity;
        });

        // Validate mã khuyến mãi chi tiết, lấy cả lý do nếu lỗi
        [$coupon, $couponError] = $this->validateCouponWithReason(
            $code,
            $userId,
            $items,
            $subtotal
        );

        if (!$coupon) {
            // Vẫn trả về summary không có coupon khi coupon không hợp lệ
            $summary = $this->buildSummary($items, null);
            return response()->json([
                'valid' => false,
                'message' => $couponError ?? 'Mã khuyến mãi không hợp lệ.',
                'summary' => $summary,
            ]);
        }


        // Tính summary với coupon
        $summary = $this->buildSummary($items, $coupon);


        $discountText = '';
        if ($coupon->discount_type === 'percent') {
            $discountText = "Giảm {$coupon->discount_value}%";
        } else {
            $discountText = "Giảm " . number_format($coupon->discount_value, 0, ',', '.') . " ₫";
        }


        return response()->json([
            'valid' => true,
            'message' => "Áp dụng mã khuyến mãi thành công: {$discountText}",
            'coupon' => [
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'discount_text' => $discountText,
            ],
            'summary' => $summary,
        ]);
    }


    /**
     * Lấy danh sách mã khuyến mãi mà user có thể sử dụng
     */
    protected function getAvailableCoupons(?int $userId): Collection
    {
        // Lấy public coupons (chưa hết hạn)
        $publicCoupons = Coupon::where(function ($query) {
            $query->where('type', 'public')
                ->orWhereNull('type'); // Backward compatibility
        })
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->get();


        // Lấy private coupons của user (nếu có userId)
        $privateCoupons = collect();
        if ($userId) {
            $privateCoupons = Coupon::where('type', 'private')
                ->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->get();
        }


        // Gộp và sắp xếp theo ngày hết hạn
        return $publicCoupons->merge($privateCoupons)
            ->sortBy('expires_at')
            ->values();
    }


    /**
     * Đảm bảo mọi cart item đều gắn với một variant (kể cả sản phẩm mặc định)
     */
    protected function guardItemsHaveVariant(Collection $items): void
    {
        $missingVariantCount = $items->filter(fn($item) => !$item->variant)->count();


        if ($missingVariantCount > 0) {
            throw new \RuntimeException('Một hoặc nhiều sản phẩm chưa có biến thể. Vui lòng tạo biến thể mặc định trước khi thanh toán.');
        }
    }


    /**
     * Lấy giá từ variant, ưu tiên giá sale. Product gốc không mang giá.
     */
    protected function getVariantPrice(ProductVariant $variant, ?CartItem $cartItem = null): float
    {
        // Nếu có cartItem với snapshot price, ưu tiên dùng snapshot (giá tại thời điểm thêm vào giỏ)
        if ($cartItem && $cartItem->price_sale_at_time !== null) {
            return (float) $cartItem->price_sale_at_time;
        }
        if ($cartItem && $cartItem->price_at_time !== null) {
            return (float) $cartItem->price_at_time;
        }
       
        // Fallback: Ưu tiên giá sale, nếu không có thì dùng giá thường (trường hợp cũ, không có snapshot)
        return (float) ($variant->price_sale ?? $variant->price ?? 0);
    }
    /**
     * Tạo URL thanh toán VNPAY cho đơn hàng chưa tạo (pending)
     */
    protected function buildVnpayUrlForPending(string $transactionId, float $total)
    {
        $vnp_Url = config('vnpay.payment_url');
        $vnp_Returnurl = config('vnpay.return_url');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $total * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang $transactionId",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $transactionId,
        );

        ksort($inputData);
       
        // Build lại dữ liệu theo chuẩn query string VNPAY
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . urlencode($value) . "&";
        }
        $hashData = rtrim($hashData, "&");
       
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        return $vnp_Url . "?" . http_build_query($inputData) . '&vnp_SecureHash=' . $vnpSecureHash;
    }

    /**
     * Tạo Order từ session data sau khi thanh toán VNPAY thành công
     */
    public function createOrderFromSession(array $sessionData): Order
    {
        return DB::transaction(function () use ($sessionData) {
            $cart = Cart::lockForUpdate()->find($sessionData['cart_id']);
            if (!$cart) {
                throw new \Exception('Giỏ hàng không tồn tại.');
            }

            // Tạo Order
            $order = Order::create([
                'cart_id' => $cart->id,
                'user_id' => $sessionData['user_id'],
                'coupon_id' => $sessionData['coupon_id'] ?? null,
                'subtotal' => $sessionData['summary']['subtotal'],
                'shipping_fee' => $sessionData['summary']['shipping_fee'],
                'discount_amount' => $sessionData['summary']['discount'],
                'total' => $sessionData['summary']['total'],
                'payment_method' => 'vnpay',
                'payment_status' => 1, // Đã thanh toán
                'status' => 'da_xac_nhan',
                'shipping_full_name' => $sessionData['shipping_data']['full_name'],
                'shipping_email' => $sessionData['shipping_data']['email'] ?? null,
                'shipping_phone' => $sessionData['shipping_data']['phone'],
                'shipping_city' => $sessionData['shipping_data']['city'] ?? null,
                'shipping_district' => $sessionData['shipping_data']['district'] ?? null,
                'shipping_ward' => $sessionData['shipping_data']['ward'] ?? null,
                'shipping_address' => $sessionData['shipping_data']['address'],
                'notes' => $sessionData['shipping_data']['notes'] ?? null,
            ]);

            // Tạo OrderItems và trừ stock
            foreach ($sessionData['items_data'] as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'product_variant_id' => $itemData['product_variant_id'],
                    'product_name' => $itemData['product_name'],
                    'product_image' => $itemData['product_image'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $itemData['unit_price'] * $itemData['quantity'],
                ]);

                // Trừ stock
                $variant = ProductVariant::lockForUpdate()->find($itemData['product_variant_id']);
                if ($variant && $variant->stock !== null) {
                    if ($variant->stock < $itemData['quantity']) {
                        throw new \Exception("Sản phẩm {$itemData['product_name']} không đủ số lượng trong kho.");
                    }
                    $newStock = $variant->stock - $itemData['quantity'];
                    $variant->update([
                        'stock' => $newStock,
                        'sold' => ($variant->sold ?? 0) + $itemData['quantity'],
                    ]);
                    if ($newStock <= 0) {
                        $variant->update(['status' => \App\Models\ProductVariant::STATUS_OUT_OF_STOCK]);
                    }
                }
            }

            // Xóa cart items
            if (!empty($sessionData['selected_item_ids'])) {
                CartItem::whereIn('id', $sessionData['selected_item_ids'])
                    ->where('cart_id', $cart->id)
                    ->delete();
                if ($cart->items()->count() === 0) {
                    $cart->update(['status' => 'converted']);
                }
            } else {
                $cart->update(['status' => 'converted']);
                $cart->items()->delete();
            }

            // Record coupon usage
            if ($sessionData['coupon_id']) {
                $coupon = Coupon::lockForUpdate()->find($sessionData['coupon_id']);
                if ($coupon) {
                    $coupon->recordUsage($sessionData['user_id'], $order->id);
                }
            }

            return $order;
        });
    }
}

