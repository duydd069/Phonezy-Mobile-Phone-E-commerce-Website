<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Tính tổng giá trị giỏ hàng dùng snapshot price (giá tại thời điểm thêm vào giỏ)
        $total = 0;
        foreach ($items as $item) {
            if ($item->variant) {
                // Ưu tiên dùng snapshot price
                $price = $item->price_sale_at_time ?? $item->price_at_time ?? $item->variant->price_sale ?? $item->variant->price ?? 0;
                $total += $price * $item->quantity;
            }
        }

        return view('client.cart.index', compact('cart', 'items', 'total'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|integer|exists:products,id', // Validate product_id
            'product_variant_id' => 'required|integer|exists:product_variants,id',
            'quantity'           => 'nullable|integer|min:1|max:10',
        ]);

        $quantity = min($request->quantity ?? 1, 10); // giới hạn 10/sp/acc
        $variantId = $request->product_variant_id;
        $productId = $request->product_id;

        // Lock variant và kiểm tra trong transaction để tránh race condition
        $variant = DB::transaction(function () use ($variantId, $productId, $quantity, $request) {
            // Lock variant row để tránh race condition
            $variant = \App\Models\ProductVariant::lockForUpdate()->find($variantId);
            
            if (!$variant) {
                throw new \Exception('Biến thể sản phẩm không tồn tại.');
            }
            
            // Validate variant thuộc đúng product (bảo mật quan trọng)
            if ($variant->product_id !== (int)$productId) {
                throw new \Exception('Biến thể không thuộc sản phẩm này.');
            }
            
            // Kiểm tra trạng thái
            if ($variant->status !== 'available') {
                throw new \Exception('Sản phẩm này hiện không khả dụng!');
            }

            // Kiểm tra stock (sau khi lock, đảm bảo không bị race condition)
            if ($variant->stock < $quantity) {
                throw new \Exception('Số lượng vượt quá tồn kho hiện có (' . $variant->stock . ').');
            }
            
            return $variant;
        });

        $cart = $this->getOrCreateActiveCart();
        $currentTotal = $cart->items()->sum('quantity');

        // Lấy snapshot giá tại thời điểm thêm vào giỏ
        $priceAtTime = $variant->price;
        $priceSaleAtTime = $variant->price_sale;

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
            // Cập nhật lại snapshot price khi cập nhật quantity (giá có thể đã thay đổi)
            $cartItem->price_at_time = $priceAtTime;
            $cartItem->price_sale_at_time = $priceSaleAtTime;
            $cartItem->save();
        } else {
            $newTotal = $currentTotal + $quantity;
            if ($newTotal > 10) {
                $message = 'Số lượng sản phẩm trong giỏ hàng vượt quá quy định (tối đa 10 sản phẩm).';
                return $this->respondError($request, $message);
            }
            // Nếu chưa có, tạo item mới với snapshot price
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
                'price_at_time'      => $priceAtTime,
                'price_sale_at_time' => $priceSaleAtTime,
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
        $variant = $cartItem->variant()->lockForUpdate()->first();
        if (!$variant) {
            return redirect()->route('cart.index')->with('error', 'Biến thể không tồn tại.');
        }

        if ($variant->status !== 'available') {
            return redirect()->route('cart.index')->with('error', 'Biến thể này không còn bán.');
        }

        if ($variant->stock < $request->quantity) {
            return redirect()->route('cart.index')->with('error', 'Số lượng vượt quá tồn kho hiện có (' . $variant->stock . ').');
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

        // Cập nhật quantity và snapshot price (nếu giá đã thay đổi, cập nhật lại snapshot)
        $cartItem->quantity = $request->quantity;
        $cartItem->price_at_time = $variant->price;
        $cartItem->price_sale_at_time = $variant->price_sale;
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
