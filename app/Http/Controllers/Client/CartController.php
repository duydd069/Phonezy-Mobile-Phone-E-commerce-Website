<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Lấy hoặc tạo giỏ hàng active cho user hiện tại
    protected function getOrCreateActiveCart()
    {
        if (auth()->check()) {
            $userId = auth()->id();
            return Cart::firstOrCreate(
                [
                    'user_id' => $userId,
                    'status'  => 'active',
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
                    ->whereNull('user_id')
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

    // Hiển thị giỏ
    public function index()
    {
        $cart = $this->getOrCreateActiveCart();

        $items = $cart->items()->with('product')->get();

        $total = 0;
        foreach ($items as $item) {
            $price = $item->product->price ?? 0;
            $total += $price * $item->quantity;
        }

        return view('client.cart.index', compact('cart', 'items', 'total'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $quantity  = $request->quantity ?? 1;
        $productId = $request->product_id;

        $cart = $this->getOrCreateActiveCart();

        // dùng cột product_variant_id để lưu product_id
        $cartItem = $cart->items()
            ->where('product_variant_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_variant_id' => $productId,
                'quantity'           => $quantity,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // Cập nhật số lượng
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateActiveCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($request->cart_item_id);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    // Xóa 1 item
    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
        ]);

        $cart = $this->getOrCreateActiveCart();
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($request->cart_item_id);

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ!');
    }

    // Xóa toàn bộ giỏ
    public function clear()
    {
        $cart = $this->getOrCreateActiveCart();
        $cart->items()->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
