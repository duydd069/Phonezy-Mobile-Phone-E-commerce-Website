<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankWebhookController extends Controller
{
    /**
     * Webhook để nhận thông báo thanh toán từ ngân hàng hoặc dịch vụ bên thứ 3
     * 
     * Cách sử dụng:
     * 1. Cấu hình webhook URL trong dịch vụ ngân hàng/dịch vụ bên thứ 3
     * 2. Khi nhận được tiền, dịch vụ sẽ gọi đến endpoint này
     * 3. Hệ thống sẽ tự động cập nhật payment_status = 'paid'
     * 
     * URL: /client/payment/bank/webhook
     */
    public function handle(Request $request)
    {
        try {
            // Kiểm tra secret key nếu có cấu hình
            $webhookSecret = config('bank.webhook_secret');
            if (!empty($webhookSecret)) {
                $providedSecret = $request->header('X-Webhook-Secret') ?? $request->input('secret');
                if ($providedSecret !== $webhookSecret) {
                    Log::warning('Bank webhook: Invalid secret', ['ip' => $request->ip()]);
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            }

            // Lấy thông tin từ request
            // Tùy vào dịch vụ ngân hàng, format có thể khác nhau
            // Bạn cần điều chỉnh theo API của dịch vụ bạn sử dụng
            
            $orderId = $request->input('order_id') ?? $request->input('orderId');
            $amount = $request->input('amount');
            $transactionId = $request->input('transaction_id') ?? $request->input('transactionId');
            $status = $request->input('status', 'success'); // 'success' hoặc 'failed'

            // Nếu không có order_id, thử parse từ nội dung chuyển khoản
            if (!$orderId && $request->has('content')) {
                // Ví dụ: nội dung chuyển khoản là "#000123"
                preg_match('/#(\d+)/', $request->input('content'), $matches);
                if (!empty($matches[1])) {
                    $orderId = $matches[1];
                }
            }

            if (!$orderId) {
                Log::warning('Bank webhook: Missing order_id', $request->all());
                return response()->json(['error' => 'Missing order_id'], 400);
            }

            $order = Order::find($orderId);

            if (!$order) {
                Log::warning('Bank webhook: Order not found', ['order_id' => $orderId]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Kiểm tra số tiền
            if ($amount && (float)$amount !== (float)$order->total) {
                Log::warning('Bank webhook: Amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $order->total,
                    'received' => $amount,
                ]);
                // Vẫn có thể xác nhận nếu bạn muốn, hoặc reject
                // return response()->json(['error' => 'Amount mismatch'], 400);
            }

            // Cập nhật trạng thái thanh toán
            if ($status === 'success' && $order->payment_status !== 'paid') {
                DB::transaction(function () use ($order, $transactionId) {
                    $order->update([
                        'payment_status' => 'paid',
                        'transaction_id' => $transactionId ?? ('BANK_' . time()),
                        'paid_at' => now(),
                    ]);
                });

                Log::info('Bank webhook: Payment confirmed', [
                    'order_id' => $order->id,
                    'transaction_id' => $transactionId,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed',
                    'order_id' => $order->id,
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment status not updated',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Bank webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'error' => 'Internal server error',
            ], 500);
        }
    }

    /**
     * Endpoint để test webhook (chỉ dùng trong development)
     * POST /client/payment/bank/webhook/test
     */
    public function test(Request $request)
    {
        if (app()->environment('production')) {
            return response()->json(['error' => 'Not available in production'], 403);
        }

        $orderId = $request->input('order_id');
        
        if (!$orderId) {
            return response()->json(['error' => 'order_id is required'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Simulate webhook call
        $testRequest = new Request([
            'order_id' => $orderId,
            'amount' => $order->total,
            'transaction_id' => 'TEST_' . time(),
            'status' => 'success',
        ]);

        return $this->handle($testRequest);
    }
}

