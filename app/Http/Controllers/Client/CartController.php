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

        // Optimize: only load necessary relationships
        $items = $cart->items()->with([
            'variant' => function($query) {
                $query->select('id', 'product_id', 'price', 'price_sale', 'stock', 'status', 'storage_id', 'version_id', 'color_id', 'image', 'sku');
            },
            'variant.product' => function($query) {
                $query->select('id', 'name', 'image', 'slug');
            },
            'variant.storage',
            'variant.version',
            'variant.color'
        ])->get();

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
            'quantity'           => 'nullable|integer|min:1|max:10',
        ]);

        $quantity = min($request->quantity ?? 1, 10); // giới hạn 10/sp/acc
        $variantId = $request->product_variant_id;

        // Kiểm tra variant có tồn tại và còn hàng không
        // LƯU Ý: Mỗi biến thể có stock riêng, không tính chung với sản phẩm gốc hay các biến thể khác
        $variant = \App\Models\ProductVariant::findOrFail($variantId);
        
        if ($variant->status !== 'available') {
            $message = 'Sản phẩm này hiện không khả dụng!';
            return $this->respondError($request, $message);
        }

        // Kiểm tra stock của biến thể này (mỗi biến thể có stock riêng)
        $currentStock = $variant->stock ?? 0;
        if ($currentStock <= 0) {
            $message = 'Sản phẩm này đã hết hàng!';
            return $this->respondError($request, $message);
        }

        if ($currentStock < $quantity) {
            $message = 'Số lượng vượt quá tồn kho hiện có (' . $currentStock . ').';
            return $this->respondError($request, $message);
        }

        $cart = $this->getOrCreateActiveCart();
        $currentTotal = $cart->items()->sum('quantity');

        // Tìm item đã có trong giỏ (do có unique constraint trên cart_id + product_variant_id)
        $cartItem = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            // Nếu đã có variant này trong giỏ, cộng dồn quantity
            $newQuantity = min($cartItem->quantity + $quantity, 10); // giới hạn 10/biến thể
            $newTotal = $currentTotal - $cartItem->quantity + $newQuantity;
            // Kiểm tra tồn kho
            if ($newQuantity > 10) {
                $message = 'Bạn chỉ được mua tối đa 10 sản phẩm mỗi loại.';
                return $this->respondError($request, $message);
            }
            if ($newTotal > 10) {
                $message = 'Số lượng sản phẩm trong giỏ hàng vượt quá quy định (tối đa 10 sản phẩm).';
                return $this->respondError($request, $message);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $newTotal = $currentTotal + $quantity;
            if ($newTotal > 10) {
                $message = 'Số lượng sản phẩm trong giỏ hàng vượt quá quy định (tối đa 10 sản phẩm).';
                return $this->respondError($request, $message);
            }
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
            'quantity'     => 'required|integer|min:1|max:10',
        ]);

        $userId = auth()->check() ? auth()->id() : 1;

        $cartItem = CartItem::whereHas('cart', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'active');
        })->findOrFail($request->cart_item_id);

        // Kiểm tra tồn kho và trạng thái của biến thể trước khi cập nhật
        // LƯU Ý: Mỗi biến thể có stock riêng, không tính chung với sản phẩm gốc hay các biến thể khác
        $variant = $cartItem->variant()->lockForUpdate()->first();
        if (!$variant) {
            return redirect()->route('cart.index')->with('error', 'Biến thể không tồn tại.');
        }

        if ($variant->status !== 'available') {
            return redirect()->route('cart.index')->with('error', 'Biến thể này không còn bán.');
        }

        // Kiểm tra stock của biến thể này (mỗi biến thể có stock riêng)
        $currentStock = $variant->stock ?? 0;
        if ($currentStock <= 0) {
            return redirect()->route('cart.index')->with('error', 'Sản phẩm này đã hết hàng!');
        }

        if ($currentStock < $request->quantity) {
            return redirect()->route('cart.index')->with('error', 'Số lượng vượt quá tồn kho hiện có (' . $currentStock . ').');
        }

        if ($request->quantity > 10) {
            return redirect()->route('cart.index')->with('error', 'Bạn chỉ được mua tối đa 10 sản phẩm mỗi loại.');
        }

        // Kiểm tra tổng số lượng trong giỏ sau khi cập nhật
        $cart = $cartItem->cart;
        $otherTotal = $cart->items()->where('id', '!=', $cartItem->id)->sum('quantity');
        $newTotal = $otherTotal + $request->quantity;
        if ($newTotal > 10) {
            return redirect()->route('cart.index')->with('error', 'Số lượng sản phẩm trong giỏ hàng vượt quá quy định (tối đa 10 sản phẩm).');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    /**
     * Helper trả về lỗi cho cả web và ajax để tránh lặp code.
     */
    protected function respondError(Request $request, string $message, int $status = 400)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }
        return redirect()->back()->with('error', $message);
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
