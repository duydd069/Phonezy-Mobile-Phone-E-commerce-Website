<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VnpayController extends Controller
{
    public function return(Request $request)
    {
        $validated = $this->validateSignature($request);
        if (!$validated['valid']) {
            return redirect()->route('client.checkout')->with('error', 'Chữ ký không hợp lệ.');
        }

        $transactionId = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        if ($responseCode === '00') {
            // Lấy thông tin đơn hàng từ session
            $sessionData = session('pending_vnpay_order');
            if (!$sessionData || $sessionData['transaction_id'] !== $transactionId) {
                return redirect()->route('client.checkout')->with('error', 'Không tìm thấy thông tin đơn hàng. Vui lòng thử lại.');
            }

            try {
                // Tạo Order từ session data
                $checkoutController = new \App\Http\Controllers\Client\CheckoutController();
                $order = $checkoutController->createOrderFromSession($sessionData);
                
                // Xóa session pending order
                session()->forget('pending_vnpay_order');
                
                // Gửi email xác nhận đơn hàng sau khi thanh toán thành công
                try {
                    $order->user?->notify(new \App\Notifications\OrderConfirmationNotification($order));
                } catch (\Exception $e) {
                    \Log::error('Failed to send VNPAY order confirmation email: ' . $e->getMessage());
                }
                
                // Use signed route to allow access without session/login persistence issues
                return redirect()->to(URL::signedRoute('client.checkout.success', ['order' => $order->id]))
                    ->with('success', 'Thanh toán VNPAY thành công!');
            } catch (\Exception $e) {
                \Log::error('Failed to create order from VNPAY session: ' . $e->getMessage());
                return redirect()->route('client.checkout')->with('error', 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng liên hệ hỗ trợ.');
            }
        }

        // Thanh toán thất bại hoặc bị hủy - xóa session pending order
        session()->forget('pending_vnpay_order');
        return redirect()->route('client.checkout')->with('error', 'Thanh toán VNPAY thất bại hoặc bị hủy.');
    }

    public function ipn(Request $request)
    {
        $validated = $this->validateSignature($request);
        if (!$validated['valid']) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid checksum']);
        }

        $transactionId = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        // IPN được gọi sau khi return, nên Order đã được tạo rồi
        // Tìm Order theo transaction_id (nếu có) hoặc tìm theo các tiêu chí khác
        $order = Order::where('payment_method', 'vnpay')
            ->where('payment_status', 1)
            ->where('status', 'da_xac_nhan')
            ->orderBy('id', 'desc')
            ->first();

        if (!$order) {
            // Nếu chưa có Order, có thể là IPN được gọi trước return
            // Trong trường hợp này, return success để VNPAY không gọi lại
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        if ($responseCode === '00') {
            // Order đã được tạo và cập nhật trong return(), chỉ cần confirm
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        return response()->json(['RspCode' => '02', 'Message' => 'Payment failed']);
    }

   protected function validateSignature(Request $request): array
{
    $vnp_SecureHash = $request->get('vnp_SecureHash');

    // Lấy toàn bộ tham số vnp_
    $inputData = [];
    foreach ($request->all() as $key => $value) {
        if (substr($key, 0, 4) === "vnp_" && $key !== "vnp_SecureHash" && $key !== "vnp_SecureHashType") {
            $inputData[$key] = $value;
        }
    }

    // Sắp xếp tăng dần theo key
    ksort($inputData);

    // Build lại dữ liệu theo chuẩn query string VNPAY
    $hashData = "";
    foreach ($inputData as $key => $value) {
        $hashData .= $key . "=" . urlencode($value) . "&";
    }
    $hashData = rtrim($hashData, "&");

    // Tạo chữ ký
    $hashSecret = config('vnpay.hash_secret');
    $calculatedHash = hash_hmac('sha512', $hashData, $hashSecret);

    return [
        'valid' => strtoupper($calculatedHash) === strtoupper($vnp_SecureHash),
        'secureHash' => $vnp_SecureHash,
        'calculated' => $calculatedHash,
        'hashData' => $hashData
    ];
}

}