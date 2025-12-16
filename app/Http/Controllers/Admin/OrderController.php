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
        $availableStatuses = array_keys(Order::getAvailableStatuses());
        
        $request->validate([
            'status' => ['required', 'in:' . implode(',', $availableStatuses)],
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Kiểm tra xem có thể chuyển trạng thái không
        if (!$order->canTransitionTo($newStatus)) {
            $oldStatusLabel = $order->status_label;
            $allStatuses = Order::getAvailableStatuses();
            $newStatusLabel = $allStatuses[$newStatus] ?? $newStatus;
            
            $errorMessage = "Không thể chuyển từ trạng thái '{$oldStatusLabel}' sang '{$newStatusLabel}'.";
            
            // Kiểm tra nếu là do chưa thanh toán
            if ($order->requiresPaymentBeforeConfirmation() && in_array($newStatus, ['confirmed', 'processing', 'shipping', 'delivered', 'completed'])) {
                $errorMessage = "Không thể xác nhận đơn hàng. Đơn hàng thanh toán qua {$order->payment_method} chưa được thanh toán. Vui lòng đợi khách hàng thanh toán trước.";
            }
            
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', $errorMessage);
        }

        // Cập nhật trạng thái
        $order->update(['status' => $newStatus]);

        // Tự động cập nhật payment_status dựa trên trạng thái đơn hàng
        $order->updatePaymentStatusBasedOnOrderStatus();

        // Tạo thông báo
        $oldStatusLabel = $order->getStatusLabelAttribute();
        $order->refresh(); // Refresh để lấy status mới
        $newStatusLabel = $order->getStatusLabelAttribute();

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', "Đã cập nhật trạng thái đơn hàng từ '{$oldStatusLabel}' sang '{$newStatusLabel}'.");
    }

    /**
     * Xác nhận thanh toán thủ công cho đơn hàng (dùng cho demo/test)
     * Chỉ áp dụng cho đơn hàng VNPay chưa thanh toán
     */
    public function confirmPayment(Request $request, Order $order): RedirectResponse
    {
        // Chỉ cho phép với đơn hàng VNPay chưa thanh toán
        if ($order->payment_method !== 'vnpay' || $order->payment_status !== 'pending') {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Chỉ có thể xác nhận thanh toán thủ công cho đơn hàng VNPay chưa thanh toán.');
        }

        // Cập nhật trạng thái thanh toán và đơn hàng
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
            'status' => 'processing', // Tự động chuyển sang đang xử lý
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Đã xác nhận thanh toán thành công (Demo mode). Đơn hàng đã được chuyển sang trạng thái "Đang xử lý".');
    }
}
