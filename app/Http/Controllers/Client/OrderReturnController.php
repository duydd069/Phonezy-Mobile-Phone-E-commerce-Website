<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\OrderReturnImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderReturnController extends Controller
{
    /**
     * Show return request form
     */
    public function create(Order $order): View|RedirectResponse
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders.index')
                ->with('error', 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Check if order can be returned
        if (!$order->canBeReturned()) {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Đơn hàng này không đủ điều kiện để hoàn trả.');
        }

        return view('client.returns.create', compact('order'));
    }

    /**
     * Store return request
     */
    public function store(Request $request, Order $order): RedirectResponse
    {
        // Check ownership
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders.index')
                ->with('error', 'Bạn không có quyền truy cập đơn hàng này.');
        }

        // Check eligibility
        if (!$order->canBeReturned()) {
            return redirect()->route('client.orders.show', $order)
                ->with('error', 'Đơn hàng này không đủ điều kiện để hoàn trả.');
        }

        // Validate request
        $validated = $request->validate([
            'contact_phone' => ['required', 'string', 'max:30'],
            'refund_method' => ['required', 'in:Ngân hàng'],
            'bank_name' => ['required_if:refund_method,Ngân hàng', 'nullable', 'string', 'max:150'],
            'bank_account_number' => ['required_if:refund_method,Ngân hàng', 'nullable', 'string', 'max:50'],
            'bank_account_name' => ['required_if:refund_method,Ngân hàng', 'nullable', 'string', 'max:150'],
            'reason' => ['required', 'string'],
            'images.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'], // 2MB max per image
        ], [
            'contact_phone.required' => 'Vui lòng nhập số điện thoại liên hệ',
            'refund_method.required' => 'Vui lòng chọn phương thức hoàn tiền',
            'bank_name.required_if' => 'Vui lòng nhập tên ngân hàng',
            'bank_account_number.required_if' => 'Vui lòng nhập số tài khoản',
            'bank_account_name.required_if' => 'Vui lòng nhập tên chủ tài khoản',
            'reason.required' => 'Vui lòng chọn lý do hoàn trả',
            'images.*.required' => 'Vui lòng tải lên ít nhất 1 ảnh',
            'images.*.image' => 'File phải là ảnh',
            'images.*.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, webp',
            'images.*.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ]);

        // Check max 5 images
        if ($request->hasFile('images') && count($request->file('images')) > 5) {
            return back()->withErrors(['images' => 'Bạn chỉ được tải lên tối đa 5 ảnh.'])->withInput();
        }

        // Create return request
        $orderReturn = OrderReturn::create([
            'order_id' => $order->id,
            'return_code' => OrderReturn::generateReturnCode(),
            'contact_phone' => $validated['contact_phone'],
            'refund_method' => $validated['refund_method'],
            'bank_name' => $validated['bank_name'] ?? null,
            'bank_account_number' => $validated['bank_account_number'] ?? null,
            'bank_account_name' => $validated['bank_account_name'] ?? null,
            'reason' => $validated['reason'],
            'status' => 'Chưa giải quyết',
            'shipping_status' => 'Chưa vận chuyển',
        ]);

        // Upload and save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('order_returns', 'public');
                
                OrderReturnImage::create([
                    'order_return_id' => $orderReturn->id,
                    'type' => 'evidence',
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('client.orders.show', $order)
            ->with('success', 'Yêu cầu hoàn trả đã được gửi thành công. Mã yêu cầu: ' . $orderReturn->return_code);
    }

    /**
     * Customer marks return as shipped
     */
    public function markAsShipped(OrderReturn $return): RedirectResponse
    {
        // Check ownership
        if ($return->order->user_id !== Auth::id()) {
            return redirect()->route('client.orders.index')
                ->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        if (!$return->canMarkAsShipped()) {
            return back()->with('error', 'Không thể cập nhật trạng thái lúc này.');
        }

        $return->markAsShipped();

        return back()->with('success', 'Đã cập nhật trạng thái: Đang vận chuyển. Admin sẽ xác nhận khi nhận được hàng.');
    }
}
