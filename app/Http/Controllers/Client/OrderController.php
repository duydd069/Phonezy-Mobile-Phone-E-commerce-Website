<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmationMail;

class OrderController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function showCheckout()
    {
        $cart = $this->getOrCreateActiveCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $total = 0;
        foreach ($items as $item) {
            $price = $item->product->price ?? 0;
            $total += $price * $item->quantity;
        }

        return view('electro.checkout', compact('cart', 'items', 'total'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
        ]);

        $cart = $this->getOrCreateActiveCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($items as $item) {
            $price = $item->product->price ?? 0;
            $total += $price * $item->quantity;
        }

        // Tạo đơn hàng
        $orderData = [
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'total_price' => $total,
            'status' => 'pending',
            'notes' => $request->notes,
            'verification_token' => Str::random(60),
        ];

        // Nếu đã đăng nhập, gán user_id
        if (auth()->check()) {
            $orderData['user_id'] = auth()->id();
            $orderData['email_verified_at'] = now(); // Người đã đăng nhập không cần xác nhận email
        } else {
            // Lưu session_id cho người chưa đăng nhập
            $orderData['session_id'] = session()->getId();
        }

        $order = Order::create($orderData);

        // Tạo order items
        foreach ($items as $item) {
            $product = $item->product;
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price_each' => $product->price ?? 0,
            ]);
        }

        // Nếu chưa đăng nhập, gửi email xác nhận
        if (!auth()->check()) {
            try {
                Mail::to($request->email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Log lỗi nhưng vẫn tiếp tục
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        $cart->items()->delete();
        $cart->update(['status' => 'completed']);

        // Lưu order_id vào session để hiển thị thông báo
        session()->put('order_id', $order->id);

        if (!auth()->check()) {
            return redirect()->route('client.checkout.success')
                ->with('success', 'Đơn hàng đã được tạo thành công! Vui lòng kiểm tra email để xác nhận đơn hàng.');
        }

        return redirect()->route('client.checkout.success')
            ->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    /**
     * Xác nhận đơn hàng qua email
     */
    public function verifyEmail($token)
    {
        $order = Order::where('verification_token', $token)
            ->whereNull('email_verified_at')
            ->first();

        if (!$order) {
            return redirect()->route('client.index')
                ->with('error', 'Link xác nhận không hợp lệ hoặc đã hết hạn!');
        }

        $order->update([
            'email_verified_at' => now(),
            'verification_token' => null,
        ]);

        return redirect()->route('client.checkout.success')
            ->with('success', 'Email đã được xác nhận thành công! Đơn hàng của bạn đang được xử lý.');
    }

    /**
     * Hiển thị trang thành công
     */
    public function success()
    {
        $orderId = session()->get('order_id');
        $order = null;

        if ($orderId) {
            $order = Order::with('items.product')->find($orderId);
            session()->forget('order_id');
        }

        return view('electro.checkout-success', compact('order'));
    }

    /**
     * Lấy hoặc tạo giỏ hàng active cho user hiện tại
     */
    protected function getOrCreateActiveCart()
    {
        if (auth()->check()) {
            $userId = auth()->id();
            return Cart::firstOrCreate(
                [
                    'user_id' => $userId,
                    'status' => 'active',
                ],
                [
                    'status' => 'active',
                ]
            );
        } else {
            // Lưu giỏ hàng vào session cho người chưa đăng nhập
            $sessionId = session()->getId();
            $cartId = session()->get('cart_id');

            if ($cartId) {
                $cart = Cart::where('id', $cartId)
                    ->where('status', 'active')
                    ->first();
                if ($cart) {
                    return $cart;
                }
            }

            // Tạo giỏ hàng mới và lưu vào session
            $cart = Cart::create([
                'user_id' => null, // null cho người chưa đăng nhập
                'status' => 'active',
            ]);

            session()->put('cart_id', $cart->id);
            return $cart;
        }
    }
}

