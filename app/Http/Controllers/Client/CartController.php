<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Concerns\HandlesActiveCart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use HandlesActiveCart;

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

        $userId = $this->resolveCartUserId();

        $cartItem = CartItem::whereHas('cart', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'active');
        })->findOrFail($request->cart_item_id);

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

        $userId = $this->resolveCartUserId();

        $cartItem = CartItem::whereHas('cart', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'active');
        })->findOrFail($request->cart_item_id);

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
