<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function return(Request $request)
    {
        $validated = $this->validateSignature($request);
        if (!$validated['valid']) {
            return redirect()->route('client.checkout')->with('error', 'Chữ ký không hợp lệ.');
        }

        $orderId = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('client.checkout')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($responseCode === '00') {
            $order->update([
                'payment_status' => 1, // 1 = đã thanh toán
                'status' => 'da_xac_nhan', // Đã xác nhận sau khi thanh toán
                'payment_method' => 'vnpay',
            ]);
            return redirect()->route('client.checkout.success', ['order' => $order->id])
                ->with('success', 'Thanh toán VNPAY thành công!');
        }

        return redirect()->route('client.checkout')->with('error', 'Thanh toán VNPAY thất bại hoặc bị hủy.');
    }

    public function ipn(Request $request)
    {
        $validated = $this->validateSignature($request);
        if (!$validated['valid']) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid checksum']);
        }

        $orderId = $request->get('vnp_TxnRef');
        $responseCode = $request->get('vnp_ResponseCode');

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        if ($responseCode === '00') {
            $order->update([
                'payment_status' => 1, // 1 = đã thanh toán
                'status' => 'da_xac_nhan', // Đã xác nhận sau khi thanh toán
                'payment_method' => 'vnpay',
            ]);
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
