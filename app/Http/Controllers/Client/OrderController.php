<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\OrderReturn;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để xem đơn hàng.');
        }

        $query = Order::where('user_id', $user->id)
            ->with(['items.product', 'coupon', 'returns'])
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

    if (!$order->canBeCancelled()) {
        return back()->with('error', 'Đơn hàng này không thể hủy. Chỉ có thể hủy khi đơn hàng đang chờ xác nhận hoặc chờ thanh toán.');
    }

    // Hoàn trả stock trước khi hủy đơn
    $order->restoreStock();

    $order->status = Order::STATUS_CANCELLED;
    $order->cancel_reason = $request->cancel_reason; // nhớ có cột này
    $order->save();

    return back()->with('success', 'Đơn hàng đã được hủy thành công.');
}

    /**
     * Show cancel & refund form
     */
    public function showCancelRefundForm(Order $order): View|RedirectResponse
    {
        $user = auth()->user();

        // Check order access permission
        if (!$user || (int) $order->user_id !== (int) $user->id) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        // Check if order has been paid
        $isPaid = $order->payment_status == 1 || $order->payment_status === 'paid';
        if (!$isPaid) {
            return redirect()->route('client.orders.index')->with('error', 'Chỉ có thể hoàn tiền cho những đơn hàng đã thanh toán.');
        }

        // Check if order is online payment method (not COD)
        $isCOD = $order->payment_method === 'cod';
        if ($isCOD) {
            return redirect()->route('client.orders.index')->with('error', 'Chỉ có thể yêu cầu hoàn tiền cho đơn hàng thanh toán online.');
        }

        // Check if admin has already confirmed (da_xac_nhan trở đi) - không cho phép hủy & hoàn tiền
        if (!in_array($order->status, ['cho_xac_nhan', 'cho_thanh_toan'])) {
            return redirect()->route('client.orders.index')->with('error', 'Không thể yêu cầu hủy & hoàn tiền khi đơn hàng đã được xác nhận.');
        }

        // Check if order already has an active return
        if ($order->returns()->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận'])->exists()) {
            return redirect()->route('client.orders.index')->with('error', 'Đơn hàng này đã có yêu cầu hoàn trả hoặc hoàn tiền.');
        }

        $order->load(['items.product']);

        return view('electro.orders.cancel-refund', compact('order'));
    }

    /**
     * Customer requests cancel + refund (bank transfer or online payment)
     */
    public function cancelAndRefund(Request $request, Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        // Check if order has been paid
        $isPaid = $order->payment_status == 1 || $order->payment_status === 'paid';
        if (!$isPaid) {
            return back()->with('error', 'Chỉ có thể hoàn tiền cho những đơn hàng đã thanh toán.');
        }

        // Check if order doesn't already have an active/rejected return
        if ($order->returns()->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận'])->exists()) {
            return back()->with('error', 'Đơn hàng này đã có yêu cầu hoàn trả hoặc hoàn tiền.');
        }

        $validated = $request->validate([
            'contact_phone' => ['required', 'string', 'max:30'],
            'bank_name' => ['required', 'string', 'max:150'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_name' => ['required', 'string', 'max:150'],
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'contact_phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'bank_name.required' => 'Vui lòng nhập tên ngân hàng',
            'bank_account_number.required' => 'Vui lòng nhập số tài khoản',
            'bank_account_name.required' => 'Vui lòng nhập tên chủ tài khoản',
            'reason.required' => 'Vui lòng nhập lý do hủy đơn',
        ]);

        // Create an OrderReturn record to hold refund info so admin can process refund
        $orderReturn = OrderReturn::create([
            'order_id' => $order->id,
            'return_code' => OrderReturn::generateReturnCode(),
            'contact_phone' => $validated['contact_phone'],
            'refund_method' => 'Ngân hàng',
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name' => $validated['bank_account_name'],
            'reason' => $validated['reason'],
            'status' => 'Chưa giải quyết',
            'shipping_status' => 'Chưa vận chuyển',
        ]);

        return redirect()->route('client.orders.index')->with('success', 'Yêu cầu hủy đơn và hoàn tiền đã được gửi. Mã: ' . $orderReturn->return_code);
    }



}
