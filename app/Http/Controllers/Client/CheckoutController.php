<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Concerns\HandlesActiveCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
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
        $cart = $this->getOrCreateActiveCart();
        // Optimize: only load necessary fields
        $items = $cart->items()->with([
            'variant' => function($query) {
                $query->select('id', 'product_id', 'price', 'price_sale', 'stock', 'status', 'sku');
            },
            'variant.product' => function($query) {
                $query->select('id', 'name', 'image', 'slug');
            }
        ])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Giới hạn tổng số lượng sản phẩm trong đơn hàng
        $totalQuantity = $items->sum('quantity');
        if ($totalQuantity > 10) {
            return redirect()->route('cart.index')
                ->with('error', 'Bạn chỉ được đặt tối đa 10 sản phẩm cho mỗi đơn hàng.');
        }

        try {
            $this->guardItemsHaveVariant($items);
            $this->guardItemsStock($items);
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Lấy coupon code từ request hoặc session
        $couponCode = request('coupon_code') ?? session('checkout_coupon_code');
        $coupon = null;
        $userId = auth()->id();
        if ($couponCode) {
            $coupon = Coupon::validateCode($couponCode, $userId);
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
            'address'   => null,
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
        $cart = $this->getOrCreateActiveCart();
        // Load variant với tất cả các trường cần thiết, đặc biệt là stock
        // Optimize: only load necessary fields
        $items = $cart->items()->with([
            'variant' => function($query) {
                $query->select('id', 'product_id', 'price', 'price_sale', 'stock', 'status', 'sku');
            },
            'variant.product' => function($query) {
                $query->select('id', 'name', 'image', 'slug');
            }
        ])->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        try {
            $this->guardItemsHaveVariant($items);
            $this->guardItemsStock($items);
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('cart.index')
                ->with('error', $e->getMessage());
        }

        $data = $request->validated();

        // Validate và lấy coupon
        $coupon = null;
        $couponCode = $data['coupon_code'] ?? null;
        $userId = auth()->id();
        if ($couponCode) {
            $coupon = Coupon::validateCode($couponCode, $userId);
            if (!$coupon) {
                return redirect()
                    ->route('client.checkout', ['coupon_code' => $couponCode])
                    ->withInput()
                    ->with('error', 'Mã khuyến mãi không hợp lệ, đã hết hạn hoặc bạn không có quyền sử dụng.');
            }
        }

        $summary = $this->buildSummary($items, $coupon);

        try {
            $order = DB::transaction(function () use ($cart, $items, $summary, $data, $coupon) {
                // Giới hạn tổng số lượng sản phẩm trong đơn hàng
                $totalQuantity = $items->sum('quantity');
                if ($totalQuantity > 10) {
                    throw new \Exception('Bạn chỉ được đặt tối đa 10 sản phẩm cho mỗi đơn hàng.');
                }

                $order = Order::create([
                    'cart_id'             => $cart->id,
                    'user_id'             => $cart->user_id,
                    'coupon_id'           => $coupon?->id,
                    'subtotal'            => $summary['subtotal'],
                    'shipping_fee'        => $summary['shipping_fee'],
                    'discount_amount'     => $summary['discount'],
                    'total'               => $summary['total'],
                    'payment_method'      => $data['payment_method'],
                    'payment_status'      => 'pending',
                    'status'              => 'pending',
                    'shipping_full_name'  => $data['full_name'],
                    'shipping_email'      => $data['email'] ?? null,
                    'shipping_phone'      => $data['phone'],
                    'shipping_city'       => $data['city'] ?? null,
                    'shipping_district'   => $data['district'] ?? null,
                    'shipping_ward'       => $data['ward'] ?? null,
                    'shipping_address'    => $data['address'],
                    'notes'               => $data['notes'] ?? null,
                ]);

                foreach ($items as $item) {
                    $variant = $item->variant;
                    if (!$variant) {
                        throw new \Exception("Sản phẩm trong giỏ hàng chưa được chọn biến thể. Vui lòng thử lại sau khi cập nhật giỏ hàng.");
                    }

                    $product = $variant->product ?? $item->product;

                    // Sử dụng giá sale nếu có, nếu không thì dùng giá thường
                    $unitPrice = $this->getVariantPrice($variant);

                    $quantity = $item->quantity;

                    OrderItem::create([
                        'order_id'          => $order->id,
                        'product_id'        => $product?->id,
                        'product_variant_id' => $variant?->id,
                        'product_name'      => $product->name ?? 'Sản phẩm',
                        'product_image'     => $product->image ?? null,
                        'quantity'          => $quantity,
                        'unit_price'        => $unitPrice,
                        'total_price'       => $unitPrice * $quantity,
                    ]);

                    // Trừ số lượng sản phẩm (stock) khi đặt hàng thành công
                    // LƯU Ý: Mỗi biến thể có stock riêng, chỉ trừ stock của biến thể này, không ảnh hưởng đến các biến thể khác
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

                        // Kiểm tra stock của biến thể này (mỗi biến thể có stock riêng)
                        if ($variant->stock < $quantity) {
                            // Nếu không đủ hàng, rollback transaction
                            throw new \Exception("Sản phẩm {$product->name} không đủ số lượng trong kho. Số lượng còn lại: {$variant->stock}");
                        }

                        // Tính toán stock mới trước khi trừ (chỉ trừ stock của biến thể này)
                        $newStock = $variant->stock - $quantity;

                        // Trừ stock và tăng sold bằng cách update trực tiếp (chỉ ảnh hưởng đến biến thể này)
                        $variant->update([
                            'stock' => $newStock,
                            'sold' => ($variant->sold ?? 0) + $quantity,
                        ]);

                        // Cập nhật status nếu hết hàng (chỉ cập nhật status của biến thể này)
                        if ($newStock <= 0) {
                            $variant->update(['status' => \App\Models\ProductVariant::STATUS_OUT_OF_STOCK]);
                        }
                    }
                }

                $cart->update(['status' => 'converted']);
                $cart->items()->delete();

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

            // Nếu chọn VNPAY, chuyển hướng sang cổng thanh toán
            if ($order->payment_method === 'vnpay') {
                $paymentUrl = $this->buildVnpayUrl($order);
                return redirect()->away($paymentUrl);
            }

            return redirect()
                ->route('client.checkout.success', ['order' => $order->id])
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
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
        $subtotal = $items->sum(function ($item) {
            $variant = $item->variant;
            return $this->getVariantPrice($variant) * $item->quantity;
        });

        $shippingFee = (float) config('checkout.shipping_fee', 0);

        // Tính discount dựa trên coupon
        $discount = 0;
        if ($coupon && $coupon->isValid()) {
            $discount = $coupon->calculateDiscount($subtotal);
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
     * Validate coupon code via AJAX
     */
    public function validateCoupon()
    {
        $code = request('code');
        $userId = auth()->id();

        // Lấy giỏ hàng để tính summary
        $cart = $this->getOrCreateActiveCart();
        // Optimize: only load necessary fields
        $items = $cart->items()->with([
            'variant' => function($query) {
                $query->select('id', 'product_id', 'price', 'price_sale', 'stock', 'status', 'sku');
            },
            'variant.product' => function($query) {
                $query->select('id', 'name', 'image', 'slug');
            }
        ])->get();

        try {
            $this->guardItemsHaveVariant($items);
            $this->guardItemsStock($items);
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

        $coupon = Coupon::validateCode($code, $userId);

        if (!$coupon) {
            // Vẫn trả về summary không có coupon khi coupon không hợp lệ
            $summary = $this->buildSummary($items, null);
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi không hợp lệ, đã hết hạn hoặc bạn không có quyền sử dụng',
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
     * Đảm bảo tất cả sản phẩm trong giỏ hàng còn hàng và đủ số lượng
     * LƯU Ý: Mỗi biến thể có stock riêng, không tính chung với sản phẩm gốc hay các biến thể khác
     */
    protected function guardItemsStock(Collection $items): void
    {
        foreach ($items as $item) {
            $variant = $item->variant;
            if (!$variant) {
                continue; // Đã được kiểm tra bởi guardItemsHaveVariant
            }

            // Kiểm tra trạng thái
            if ($variant->status !== 'available') {
                $productName = $variant->product->name ?? 'Sản phẩm';
                throw new \RuntimeException("Sản phẩm '{$productName}' hiện không khả dụng!");
            }

            // Kiểm tra stock của biến thể này (mỗi biến thể có stock riêng)
            $currentStock = $variant->stock ?? 0;
            if ($currentStock <= 0) {
                $productName = $variant->product->name ?? 'Sản phẩm';
                throw new \RuntimeException("Sản phẩm '{$productName}' đã hết hàng!");
            }

            if ($currentStock < $item->quantity) {
                $productName = $variant->product->name ?? 'Sản phẩm';
                throw new \RuntimeException("Sản phẩm '{$productName}' không đủ số lượng trong kho. Số lượng còn lại: {$currentStock}");
            }
        }
    }

    /**
     * Lấy giá từ variant, ưu tiên giá sale. Product gốc không mang giá.
     */
    protected function getVariantPrice(ProductVariant $variant): float
    {
        return (float) ($variant->price_sale ?? $variant->price ?? 0);
    }

    /**
     * Tạo URL thanh toán VNPAY
     */
    protected function buildVnpayUrl(Order $order)
    {
        $vnp_Url = config('vnpay.payment_url');
        $vnp_Returnurl = config('vnpay.return_url');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $order->total * 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang $order->id",
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $order->id,
        );

        ksort($inputData);
        
        // Build lại dữ liệu theo chuẩn query string VNPAY (giống validateSignature)
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . urlencode($value) . "&";
        }
        $hashData = rtrim($hashData, "&");
        
        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

//         \Log::channel('single')->info('VNPAY DEBUG', [
//     'hashData' => $hashData,
//     'query' => $queryString,
//     'secureHash' => $vnpSecureHash,
// ]);

        return $vnp_Url . "?" . http_build_query($inputData) . '&vnp_SecureHash=' . $vnpSecureHash;
    }
}
