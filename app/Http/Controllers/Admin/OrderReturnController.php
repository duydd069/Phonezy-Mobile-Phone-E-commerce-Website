<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * Display a listing of return requests
     */
    public function index(Request $request): View
    {
        $query = OrderReturn::with(['order', 'order.user'])->orderByDesc('created_at');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by return code or order ID
        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('return_code', 'like', "%{$search}%")
                  ->orWhere('order_id', $search);
            });
        }

        $returns = $query->paginate(15)->withQueryString();

        return view('admin.returns.index', compact('returns'));
    }

    /**
     * Display the specified return request
     */
    public function show(OrderReturn $return): View
    {
        $return->load(['order', 'order.user', 'order.items', 'images', 'refundedBy']);

        return view('admin.returns.show', compact('return'));
    }

    /**
     * Approve return request
     */
    public function approve(OrderReturn $return): RedirectResponse
    {
        if (!$return->canApprove()) {
            return back()->with('error', 'Không thể phê duyệt yêu cầu này.');
        }

        $return->approve();

        return back()->with('success', 'Đã phê duyệt yêu cầu hoàn trả. Khách hàng có thể gửi hàng.');
    }

    /**
     * Reject return request
     */
    public function reject(Request $request, OrderReturn $return): RedirectResponse
    {
        if (!$return->canReject()) {
            return back()->with('error', 'Không thể từ chối yêu cầu này.');
        }

        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'min:10'],
        ], [
            'admin_note.required' => 'Vui lòng nhập lý do từ chối',
            'admin_note.min' => 'Lý do phải có ít nhất 10 ký tự',
        ]);

        $return->reject($validated['admin_note']);

        return back()->with('success', 'Đã từ chối yêu cầu hoàn trả.');
    }

    /**
     * Confirm received return
     */
    public function confirmReceived(OrderReturn $return): RedirectResponse
    {
        if (!$return->canConfirmReceived()) {
            return back()->with('error', 'Không thể xác nhận nhận hàng lúc này.');
        }

        $return->confirmReceived();

        return back()->with('success', 'Đã xác nhận nhận được hàng hoàn trả. Vui lòng xử lý hoàn tiền.');
    }

    /**
     * Process refund
     */
    public function processRefund(Request $request, OrderReturn $return): RedirectResponse
    {
        if (!$return->canProcessRefund()) {
            return back()->with('error', 'Không thể xử lý hoàn tiền lúc này.');
        }

        // Validate refund proof images
        $validated = $request->validate([
            'refund_images.*' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ], [
            'refund_images.*.required' => 'Vui lòng tải lên ảnh minh chứng hoàn tiền',
            'refund_images.*.image' => 'File phải là ảnh',
            'refund_images.*.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, webp',
            'refund_images.*.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ]);

        // Upload refund proof images
        if ($request->hasFile('refund_images')) {
            foreach ($request->file('refund_images') as $image) {
                $path = $image->store('order_returns/refund_proofs', 'public');
                
                OrderReturnImage::create([
                    'order_return_id' => $return->id,
                    'type' => 'refund_proof',
                    'path' => $path,
                ]);
            }
        }

        // Process refund
        $return->processRefund(Auth::id());

        return back()->with('success', 'Đã hoàn tiền thành công. Trạng thái đơn hàng đã được cập nhật.');
    }
}
