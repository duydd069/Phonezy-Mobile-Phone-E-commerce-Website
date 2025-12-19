<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách wishlist của user
     */
    public function index()
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để xem wishlist.');
        }

        $wishlists = Wishlist::where('user_id', $userId)
            ->with(['product.category', 'product.brand', 'product.variants' => function($query) {
                $query->where('status', 'available')->orderBy('price', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('electro.wishlist.index', compact('wishlists'));
    }

    /**
     * Thêm sản phẩm vào wishlist
     */
    public function add(Request $request)
    {
        $userId = auth()->id();
        
        if (!$userId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm vào wishlist.'
                ], 401);
            }
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để thêm vào wishlist.');
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->product_id;

        // Kiểm tra xem sản phẩm đã có trong wishlist chưa
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $message = 'Sản phẩm đã có trong wishlist.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'in_wishlist' => true
                ]);
            }
            return redirect()->back()->with('info', $message);
        }

        // Thêm vào wishlist
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        $message = 'Đã thêm sản phẩm vào wishlist!';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => true
            ]);
        }
        return redirect()->back()->with('success', $message);
    }

    /**
     * Xóa sản phẩm khỏi wishlist
     */
    public function remove(Request $request)
    {
        $userId = auth()->id();
        
        if (!$userId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập.'
                ], 401);
            }
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập.');
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->product_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlist) {
            $message = 'Sản phẩm không có trong wishlist.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'in_wishlist' => false
                ]);
            }
            return redirect()->back()->with('error', $message);
        }

        $wishlist->delete();

        $message = 'Đã xóa sản phẩm khỏi wishlist!';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => false
            ]);
        }
        return redirect()->back()->with('success', $message);
    }

    /**
     * Toggle wishlist (thêm nếu chưa có, xóa nếu đã có)
     */
    public function toggle(Request $request)
    {
        $userId = auth()->id();
        
        if (!$userId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để thêm vào wishlist.'
                ], 401);
            }
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để thêm vào wishlist.');
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = $request->product_id;

        $wishlist = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            // Xóa nếu đã có
            $wishlist->delete();
            $message = 'Đã xóa sản phẩm khỏi wishlist!';
            $inWishlist = false;
        } else {
            // Thêm nếu chưa có
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $message = 'Đã thêm sản phẩm vào wishlist!';
            $inWishlist = true;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'in_wishlist' => $inWishlist
            ]);
        }
        return redirect()->back()->with('success', $message);
    }
}
