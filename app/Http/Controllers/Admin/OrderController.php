<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items'])->orderByDesc('created_at');

        // Tìm kiếm
        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('shipping_full_name', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhere('shipping_email', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo trạng thái thanh toán
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items']);
        $paymentMethods = config('checkout.payment_methods', []);

        return view('admin.orders.show', compact('order', 'paymentMethods'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Nếu đơn hàng hoàn thành, có thể cập nhật payment_status
        if ($request->status === 'completed' && $order->payment_status === 'pending') {
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Order status updated from '{$oldStatus}' to '{$request->status}'.");
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);

        $oldPaymentStatus = $order->payment_status;
        
        $updateData = ['payment_status' => $request->payment_status];
        
        // Nếu thanh toán thành công, cập nhật paid_at
        if ($request->payment_status === 'paid' && $order->payment_status !== 'paid') {
            $updateData['paid_at'] = now();
        }
        
        // Nếu hủy thanh toán, xóa paid_at
        if ($request->payment_status !== 'paid') {
            $updateData['paid_at'] = null;
        }

        $order->update($updateData);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Payment status updated from '{$oldPaymentStatus}' to '{$request->payment_status}'.");
    }
}

