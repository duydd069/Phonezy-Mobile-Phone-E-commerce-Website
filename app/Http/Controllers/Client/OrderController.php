<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        $query = Order::where('user_id', $user->id)
            ->with(['items.product', 'coupon'])
            ->orderByDesc('created_at');

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('electro.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $user = auth()->user();

        // Check order access permission
        if (!$user || (int) $order->user_id !== (int) $user->id) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['items', 'user', 'coupon', 'returns.images']);
        $paymentMethods = config('checkout.payment_methods', []);

        return view('electro.orders.show', compact('order', 'paymentMethods'));
    }

    public function cancel(Request $request, Order $order)
{
    if (auth()->id() !== $order->user_id) {
        abort(403);
    }

    if ($order->status !== Order::STATUS_PENDING) {
        return back()->with('error', 'Đơn hàng này không thể hủy.');
    }

    // Hoàn trả stock trước khi hủy đơn
    $order->restoreStock();

    $order->status = Order::STATUS_CANCELLED;
    $order->cancel_reason = $request->cancel_reason; // nhớ có cột này
    $order->save();

    return back()->with('success', 'Đơn hàng đã được hủy thành công.');
}



}
