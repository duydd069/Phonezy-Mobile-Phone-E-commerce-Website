<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Concerns\HandlesActiveCart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use HandlesActiveCart;

    public function show()
    {
        $cart = $this->getOrCreateActiveCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $summary = $this->buildSummary($items);
        $paymentMethods = config('checkout.payment_methods', []);
        $user = auth()->user();

        $prefill = [
            'full_name' => $user?->name,
            'email'     => $user?->email,
            'phone'     => $user?->phone,
            'address'   => null,
            'city'      => null,
            'district'  => null,
            'ward'      => null,
            'notes'     => null,
        ];

        return view('electro.checkout', compact('cart', 'items', 'summary', 'paymentMethods', 'prefill'));
    }

    public function store(CheckoutRequest $request)
    {
        $cart = $this->getOrCreateActiveCart();
        $items = $cart->items()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $summary = $this->buildSummary($items);
        $data = $request->validated();

        $order = DB::transaction(function () use ($cart, $items, $summary, $data) {
            $order = Order::create([
                'cart_id'             => $cart->id,
                'user_id'             => $cart->user_id,
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
                $product = $item->product;
                $unitPrice = $product->price ?? 0;
                $quantity = $item->quantity;

                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $product?->id,
                    'product_name'  => $product->name ?? 'Sản phẩm',
                    'product_image' => $product->image ?? null,
                    'quantity'      => $quantity,
                    'unit_price'    => $unitPrice,
                    'total_price'   => $unitPrice * $quantity,
                ]);
            }

            $cart->update(['status' => 'converted']);
            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('client.checkout.success', $order)
            ->with('success', 'Đặt hàng thành công!');
    }

    public function success(Order $order)
    {
        $userId = $this->resolveCartUserId();

        if ((int) $order->user_id !== $userId) {
            abort(404);
        }

        $order->load('items');

        return view('electro.checkout-success', [
            'order'           => $order,
            'paymentMethods'  => config('checkout.payment_methods', []),
        ]);
    }

    protected function buildSummary(Collection $items): array
    {
        $subtotal = $items->sum(function ($item) {
            $price = $item->product->price ?? 0;
            return $price * $item->quantity;
        });

        $shippingFee = (float) config('checkout.shipping_fee', 0);
        $discount = 0;
        $total = max($subtotal - $discount + $shippingFee, 0);

        return [
            'subtotal'     => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount'     => $discount,
            'total'        => $total,
        ];
    }
}


