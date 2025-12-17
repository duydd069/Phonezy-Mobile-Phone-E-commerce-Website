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
     * Tự động tạo mã giao dịch test cho sandbox
     */
    public function confirmPayment(Request $request, Order $order): RedirectResponse
    {
        // Chỉ cho phép với đơn hàng VNPay chưa thanh toán
        if ($order->payment_method !== 'vnpay' || $order->payment_status !== 'pending') {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Chỉ có thể xác nhận thanh toán thủ công cho đơn hàng VNPay chưa thanh toán.');
        }

        // Tạo mã giao dịch test cho sandbox (format: SANDBOX_ORDERID_TIMESTAMP)
        // Mã này có thể dùng để test hoàn tiền trong sandbox
        $testTransactionNo = 'SANDBOX_' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '_' . time();
        $testTransactionDate = now();

        // Cập nhật trạng thái thanh toán và đơn hàng
        $order->update([
            'payment_status' => 'paid',
            'paid_at' => $testTransactionDate,
            'status' => 'processing', // Tự động chuyển sang đang xử lý
            'transaction_no' => $testTransactionNo, // Tạo mã giao dịch test
            'transaction_date' => $testTransactionDate,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Đã xác nhận thanh toán thành công (Demo mode). Mã giao dịch test: ' . $testTransactionNo . '. Đơn hàng đã được chuyển sang trạng thái "Đang xử lý".');
    }

    /**
     * Tạo mã giao dịch test cho đơn hàng đã thanh toán nhưng chưa có mã giao dịch
     * Dùng cho sandbox khi quét QR nhưng chưa chuyển khoản
     */
    public function generateTestTransaction(Order $order): RedirectResponse
    {
        if ($order->payment_method !== 'vnpay') {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Chỉ có thể tạo mã giao dịch test cho đơn hàng VNPAY.');
        }

        if ($order->payment_status !== 'paid') {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Chỉ có thể tạo mã giao dịch test cho đơn hàng đã thanh toán.');
        }

        if ($order->transaction_no) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('info', 'Đơn hàng đã có mã giao dịch: ' . $order->transaction_no);
        }

        // Tạo mã giao dịch test cho sandbox
        $testTransactionNo = 'SANDBOX_' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . '_' . time();
        $testTransactionDate = $order->paid_at ?? now();

        $order->update([
            'transaction_no' => $testTransactionNo,
            'transaction_date' => $testTransactionDate,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Đã tạo mã giao dịch test: ' . $testTransactionNo . '. Bạn có thể dùng mã này để test hoàn tiền trong sandbox.');
    }

    /**
     * Thực hiện hoàn tiền cho đơn hàng VNPAY
     */
    public function refund(Request $request, Order $order): RedirectResponse
    {
        try {
            // Kiểm tra quyền và điều kiện
            if ($order->payment_method !== 'vnpay') {
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', 'Chỉ có thể hoàn tiền cho đơn hàng thanh toán qua VNPAY.');
            }

            if ($order->payment_status !== 'paid') {
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', 'Chỉ có thể hoàn tiền cho đơn hàng đã thanh toán.');
            }

            if ($order->payment_status === 'refunded') {
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', 'Đơn hàng đã được hoàn tiền trước đó.');
            }

            // Kiểm tra trạng thái đơn hàng có cho phép hoàn tiền không
            $allowedStatusesForRefund = ['processing', 'shipping', 'delivered', 'completed'];
            if (!in_array($order->status, $allowedStatusesForRefund)) {
                $statusLabel = $order->status_label;
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', "Không thể hoàn tiền cho đơn hàng ở trạng thái '{$statusLabel}'. Chỉ có thể hoàn tiền khi đơn hàng đang xử lý, đang giao, đã giao hoặc đã hoàn thành.");
            }

            // Lấy thông tin từ request (nếu có)
            $amount = $request->input('amount') ? (float) $request->input('amount') : null;
            $transactionNo = $request->input('transaction_no');
            $transactionDate = $request->input('transaction_date');

            // Log thông tin để debug
            \Log::info('VNPAY Refund Request', [
                'order_id' => $order->id,
                'amount' => $amount,
                'transaction_no' => $transactionNo ?? $order->transaction_no,
                'order_transaction_no' => $order->transaction_no,
                'order_status' => $order->status,
                'payment_status' => $order->payment_status,
            ]);

            // Gọi method refund từ VnpayController
            $vnpayController = new \App\Http\Controllers\Client\VnpayController();
            $result = $vnpayController->refund($order, $amount, $transactionNo, $transactionDate);

            if ($result['success']) {
                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('success', $result['message']);
            } else {
                // Log lỗi để debug
                \Log::error('VNPAY Refund Failed', [
                    'order_id' => $order->id,
                    'error' => $result['message'],
                    'response_code' => $result['response_code'] ?? null,
                    'response_data' => $result['response_data'] ?? null,
                ]);

                return redirect()
                    ->route('admin.orders.show', $order)
                    ->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            // Log exception để debug
            \Log::error('VNPAY Refund Exception', [
                'order_id' => $order->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Đã xảy ra lỗi khi thực hiện hoàn tiền: ' . $e->getMessage() . '. Vui lòng kiểm tra log để biết thêm chi tiết.');
        }
    }

    /**
     * Tra cứu mã giao dịch VNPAY từ API
     */
    public function queryTransaction(Order $order): RedirectResponse
    {
        if ($order->payment_method !== 'vnpay') {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Chỉ có thể tra cứu cho đơn hàng thanh toán qua VNPAY.');
        }

        // Gọi method queryTransaction từ VnpayController
        $vnpayController = new \App\Http\Controllers\Client\VnpayController();
        $result = $vnpayController->queryTransaction($order);

        if ($result['success']) {
            $message = $result['message'];
            if (isset($result['transaction_no'])) {
                $message .= ' Mã giao dịch: ' . $result['transaction_no'];
            }
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('success', $message);
        } else {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', $result['message']);
        }
    }
}
