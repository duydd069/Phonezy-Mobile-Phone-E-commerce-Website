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
        // Nếu chưa có login thì tạm fix user_id = 1
        $userId = auth()->check() ? auth()->id() : 1;

        return Cart::firstOrCreate(
            [
                'user_id' => $userId,
                'status'  => 'active',
            ],
            [
                'status' => 'active',
            ]
        );
    }

    // Hiển thị giỏ
    public function index()
    {
        $cart = $this->getOrCreateActiveCart();

        $items = $cart->items()->with(['variant.product', 'variant.storage', 'variant.version', 'variant.color'])->get();

        $total = 0;
        foreach ($items as $item) {
            if ($item->variant) {
                $price = $item->variant->price_sale ?? $item->variant->price ?? 0;
                $total += $price * $item->quantity;
            }
        }

        return view('client.cart.index', compact('cart', 'items', 'total'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity'           => 'nullable|integer|min:1',
        ]);

        $quantity = $request->quantity ?? 1;
        $variantId = $request->product_variant_id;

        // Kiểm tra variant có tồn tại và còn hàng không
        $variant = \App\Models\ProductVariant::findOrFail($variantId);
        
        if ($variant->status !== 'available') {
            $message = 'Sản phẩm này hiện không khả dụng!';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($variant->stock < $quantity) {
            $message = 'Số lượng sản phẩm không đủ!';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->back()->with('error', $message);
        }

        $cart = $this->getOrCreateActiveCart();

        // Tìm item đã có trong giỏ (do có unique constraint trên cart_id + product_variant_id)
        $cartItem = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            // Nếu đã có variant này trong giỏ, cộng dồn quantity
            $newQuantity = $cartItem->quantity + $quantity;
            // Kiểm tra tồn kho
            if ($newQuantity > $variant->stock) {
                $message = 'Số lượng trong giỏ hàng vượt quá tồn kho!';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->back()->with('error', $message);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo item mới
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        $message = 'Đã thêm sản phẩm vào giỏ hàng!';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    // Cập nhật số lượng
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        $userId = auth()->check() ? auth()->id() : 1;

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

        $userId = auth()->check() ? auth()->id() : 1;

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
