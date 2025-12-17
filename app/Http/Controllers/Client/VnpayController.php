<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
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
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_method' => 'vnpay',
                'transaction_no' => $request->get('vnp_TransactionNo'),
                'transaction_date' => $request->get('vnp_PayDate') ? 
                    \Carbon\Carbon::createFromFormat('YmdHis', $request->get('vnp_PayDate')) : now(),
                'paid_at' => now(),
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
                'payment_status' => 'paid',
                'status' => 'processing',
                'payment_method' => 'vnpay',
                'transaction_no' => $request->get('vnp_TransactionNo'),
                'transaction_date' => $request->get('vnp_PayDate') ? 
                    \Carbon\Carbon::createFromFormat('YmdHis', $request->get('vnp_PayDate')) : now(),
                'paid_at' => now(),
            ]);
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        return response()->json(['RspCode' => '02', 'Message' => 'Payment failed']);
    }

    /**
     * Thực hiện hoàn tiền qua VNPAY
     */
    public function refund(Order $order, ?float $amount = null, ?string $transactionNo = null, ?string $transactionDate = null)
    {
        // Kiểm tra đơn hàng có thể hoàn tiền
        if ($order->payment_method !== 'vnpay') {
            return [
                'success' => false,
                'message' => 'Đơn hàng không sử dụng phương thức thanh toán VNPAY.'
            ];
        }

        // Sử dụng method canBeRefunded() từ Order model để kiểm tra
        if (!$order->canBeRefunded()) {
            $reasons = [];
            
            if ($order->payment_status !== 'paid') {
                $reasons[] = 'Đơn hàng chưa thanh toán';
            }
            
            if ($order->payment_status === 'refunded') {
                $reasons[] = 'Đơn hàng đã được hoàn tiền trước đó';
            }
            
            $allowedStatusesForRefund = ['processing', 'shipping', 'delivered', 'completed'];
            if (!in_array($order->status, $allowedStatusesForRefund)) {
                $reasons[] = 'Trạng thái đơn hàng "' . $order->status_label . '" không cho phép hoàn tiền';
            }
            
            $message = 'Không thể hoàn tiền. ' . implode(', ', $reasons) . '.';
            
            return [
                'success' => false,
                'message' => $message
            ];
        }

        // Lấy thông tin cấu hình
        $vnp_Url = config('vnpay.refund_url');
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Version = config('vnpay.version', '2.1.0');

        // Số tiền hoàn (mặc định là toàn bộ, tính bằng xu)
        $refundAmount = ($amount ?? $order->total) * 100;

        // Tạo RequestId duy nhất (format: order_id_timestamp)
        $vnp_RequestId = $order->id . '_' . time();

        // LƯU Ý QUAN TRỌNG:
        // - vnp_TxnRef: Mã đơn hàng (Order ID) - dùng để tham chiếu
        // - vnp_TransactionNo: Mã giao dịch VNPAY - BẮT BUỘC để hoàn tiền (KHÔNG phải mã đơn hàng!)
        //   Mã này được VNPAY trả về sau khi thanh toán thành công qua Return URL hoặc IPN
        
        // TransactionNo và TransactionDate từ giao dịch gốc
        // Ưu tiên sử dụng transaction_no đã lưu trong order (đã được lưu tự động khi thanh toán thành công)
        $vnp_TransactionNo = $transactionNo ?? $order->transaction_no;
        $vnp_TransactionDate = $transactionDate ?? ($order->transaction_date ? $order->transaction_date->format('YmdHis') : $order->created_at->format('YmdHis'));
        
        // Nếu vẫn không có mã giao dịch VNPAY, tạo giá trị mặc định cho sandbox test
        // (Trong production, phải có mã giao dịch thật từ VNPAY)
        if (!$vnp_TransactionNo) {
            // Nếu là mã test (bắt đầu bằng SANDBOX_), giữ nguyên
            // Nếu không có, tạo mã mặc định cho sandbox
            if (strpos($order->transaction_no ?? '', 'SANDBOX_') === 0) {
                $vnp_TransactionNo = $order->transaction_no;
            } else {
                $vnp_TransactionNo = $order->id . '000000'; // Chỉ dùng cho sandbox test
            }
        }
        if (!$vnp_TransactionDate) {
            $vnp_TransactionDate = $order->created_at->format('YmdHis');
        }

        // Chuẩn bị dữ liệu request
        $inputData = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => $vnp_Version,
            "vnp_Command" => "refund",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TransactionType" => "03", // Refund transaction type
            "vnp_TxnRef" => $order->id, // Mã đơn hàng (Order ID) - để tham chiếu
            "vnp_Amount" => $refundAmount,
            "vnp_OrderInfo" => "Hoan tien don hang #{$order->id}",
            "vnp_TransactionNo" => $vnp_TransactionNo, // Mã giao dịch VNPAY (BẮT BUỘC) - KHÔNG phải mã đơn hàng!
            "vnp_TransactionDate" => $vnp_TransactionDate, // Ngày giao dịch gốc
            "vnp_CreateBy" => auth()->user()->name ?? "Admin",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_IpAddr" => request()->ip(),
        ];

        // Sắp xếp và tạo chữ ký
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . urlencode($value) . "&";
        }
        $hashData = rtrim($hashData, "&");
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;

        // Log request data để debug (không log hash secret)
        $logData = $inputData;
        unset($logData['vnp_SecureHash']);
        \Log::info('VNPAY Refund API Request', [
            'order_id' => $order->id,
            'url' => $vnp_Url,
            'request_data' => $logData,
            'transaction_no' => $vnp_TransactionNo,
            'transaction_date' => $vnp_TransactionDate,
        ]);

        // Tạo record refund trong database (status = pending)
        $refundAmount = $amount ?? $order->total;
        $refund = Refund::create([
            'order_id' => $order->id,
            'amount' => $refundAmount,
            'transaction_no' => $vnp_TransactionNo,
            'refunded_by' => auth()->id(),
            'status' => 'pending',
        ]);

        // Gọi API VNPAY
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $vnp_Url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($inputData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout 30 giây
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Connection timeout 10 giây

            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Kiểm tra lỗi curl
            if ($curlError) {
                \Log::error('VNPAY Refund CURL Error', [
                    'order_id' => $order->id,
                    'error' => $curlError,
                ]);
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối đến VNPAY: ' . $curlError
                ];
            }

            if ($httpCode !== 200) {
                \Log::error('VNPAY Refund HTTP Error', [
                    'order_id' => $order->id,
                    'http_code' => $httpCode,
                    'response' => $response,
                ]);
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối đến VNPAY. Mã lỗi HTTP: ' . $httpCode
                ];
            }

            // Log response để debug
            \Log::info('VNPAY Refund API Response', [
                'order_id' => $order->id,
                'http_code' => $httpCode,
                'raw_response' => $response,
            ]);

            // Parse response
            if (empty($response)) {
                \Log::error('VNPAY Refund Empty Response', [
                    'order_id' => $order->id,
                    'http_code' => $httpCode,
                ]);
                return [
                    'success' => false,
                    'message' => 'VNPAY không trả về dữ liệu. Mã HTTP: ' . $httpCode
                ];
            }

            parse_str($response, $responseData);

            // Kiểm tra response code
            $responseCode = $responseData['vnp_ResponseCode'] ?? '99';
            
            \Log::info('VNPAY Refund Parsed Response', [
                'order_id' => $order->id,
                'response_code' => $responseCode,
                'response_data' => $responseData,
            ]);

            if ($responseCode === '00') {
                // Hoàn tiền thành công - cập nhật refund record
                $refund->update([
                    'status' => 'success',
                    'transaction_no' => $responseData['vnp_TransactionNo'] ?? $vnp_TransactionNo,
                    'vnpay_response_code' => $responseCode,
                    'vnpay_response_data' => $responseData,
                    'refunded_at' => now(),
                ]);

                // Sử dụng method processRefund() từ Order model để cập nhật trạng thái đơn hàng
                $refundResult = $order->processRefund($amount);
                
                if ($refundResult['success']) {
                    return [
                        'success' => true,
                        'message' => $refundResult['message'],
                        'transaction_no' => $responseData['vnp_TransactionNo'] ?? null,
                        'refund_amount' => $amount ?? $order->total,
                        'refund_id' => $refund->id,
                        'response_data' => $responseData,
                        'status_changes' => [
                            'old_status' => $refundResult['old_status'],
                            'new_status' => $refundResult['new_status'],
                        ]
                    ];
                } else {
                    // Nếu processRefund() thất bại, vẫn trả về thành công từ VNPAY nhưng cảnh báo
                    return [
                        'success' => true,
                        'message' => 'Hoàn tiền thành công từ VNPAY nhưng có lỗi khi cập nhật trạng thái đơn hàng: ' . $refundResult['message'],
                        'transaction_no' => $responseData['vnp_TransactionNo'] ?? null,
                        'refund_id' => $refund->id,
                        'response_data' => $responseData
                    ];
                }
            } else {
                // Hoàn tiền thất bại - cập nhật refund record
                $refund->update([
                    'status' => 'failed',
                    'vnpay_response_code' => $responseCode,
                    'vnpay_response_data' => $responseData,
                ]);

                $errorMessages = [
                    '02' => 'Mã terminal không hợp lệ',
                    '03' => 'Định dạng dữ liệu không hợp lệ',
                    '91' => 'Không tìm thấy giao dịch',
                    '94' => 'Yêu cầu trùng lặp trong khoảng thời gian cho phép',
                    '97' => 'Chữ ký không hợp lệ',
                    '99' => 'Lỗi khác'
                ];

                $errorMessage = $errorMessages[$responseCode] ?? "Lỗi không xác định (Mã: {$responseCode})";
                $vnpayMessage = $responseData['vnp_Message'] ?? '';

                return [
                    'success' => false,
                    'message' => "Hoàn tiền thất bại: {$errorMessage}" . ($vnpayMessage ? " - {$vnpayMessage}" : ''),
                    'response_code' => $responseCode,
                    'response_data' => $responseData,
                    'refund_id' => $refund->id,
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi gọi API VNPAY: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Tra cứu mã giao dịch VNPAY từ API
     */
    public function queryTransaction(Order $order)
    {
        if ($order->payment_method !== 'vnpay') {
            return [
                'success' => false,
                'message' => 'Đơn hàng không sử dụng phương thức thanh toán VNPAY.'
            ];
        }

        // Lấy thông tin cấu hình
        $vnp_Url = config('vnpay.refund_url'); // Dùng chung endpoint với refund
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Version = config('vnpay.version', '2.1.0');

        // Tạo RequestId duy nhất
        $vnp_RequestId = 'QUERY_' . $order->id . '_' . time();
        
        // TransactionDate từ ngày tạo đơn hoặc transaction_date
        $vnp_TransactionDate = $order->transaction_date ? 
            $order->transaction_date->format('YmdHis') : 
            $order->created_at->format('YmdHis');

        // Chuẩn bị dữ liệu request
        $inputData = [
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => $vnp_Version,
            "vnp_Command" => "querydr", // Query transaction
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TxnRef" => $order->id, // Mã đơn hàng
            "vnp_OrderInfo" => "Tra cuu giao dich don hang #{$order->id}",
            "vnp_TransactionDate" => $vnp_TransactionDate,
        ];

        // Nếu đã có transaction_no, thêm vào để query chính xác hơn
        if ($order->transaction_no) {
            $inputData["vnp_TransactionNo"] = $order->transaction_no;
        }

        // Sắp xếp và tạo chữ ký
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . "=" . urlencode($value) . "&";
        }
        $hashData = rtrim($hashData, "&");
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;

        // Gọi API VNPAY
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $vnp_Url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($inputData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return [
                    'success' => false,
                    'message' => 'Lỗi kết nối đến VNPAY. Mã lỗi HTTP: ' . $httpCode
                ];
            }

            // Parse response
            parse_str($response, $responseData);

            // Kiểm tra response code
            $responseCode = $responseData['vnp_ResponseCode'] ?? '99';

            if ($responseCode === '00') {
                // Tra cứu thành công - cập nhật mã giao dịch nếu chưa có
                $transactionNo = $responseData['vnp_TransactionNo'] ?? null;
                $payDate = $responseData['vnp_PayDate'] ?? null;

                if ($transactionNo && !$order->transaction_no) {
                    $order->update([
                        'transaction_no' => $transactionNo,
                        'transaction_date' => $payDate ? 
                            \Carbon\Carbon::createFromFormat('YmdHis', $payDate) : now(),
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Tra cứu thành công!',
                    'transaction_no' => $transactionNo,
                    'transaction_date' => $payDate,
                    'response_data' => $responseData
                ];
            } else {
                $errorMessages = [
                    '01' => 'Không tìm thấy giao dịch',
                    '02' => 'Mã terminal không hợp lệ',
                    '03' => 'Định dạng dữ liệu không hợp lệ',
                    '97' => 'Chữ ký không hợp lệ',
                    '99' => 'Lỗi khác'
                ];

                $errorMessage = $errorMessages[$responseCode] ?? "Lỗi không xác định (Mã: {$responseCode})";
                $vnpayMessage = $responseData['vnp_Message'] ?? '';

                return [
                    'success' => false,
                    'message' => "Tra cứu thất bại: {$errorMessage}" . ($vnpayMessage ? " - {$vnpayMessage}" : ''),
                    'response_code' => $responseCode,
                    'response_data' => $responseData
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi gọi API VNPAY: ' . $e->getMessage()
            ];
        }
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
