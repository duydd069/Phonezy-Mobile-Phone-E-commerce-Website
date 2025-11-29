<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MoMoService;
use App\Notifications\OrderConfirmationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PaymentController extends Controller
{
    protected $momoService;

    public function __construct(MoMoService $momoService)
    {
        $this->momoService = $momoService;
    }

    /**
     * Trang mock payment để test (chỉ dùng khi MOMO_ENVIRONMENT=mock)
     */
    public function momoMock(Request $request)
    {
        $orderId = $request->get('orderId');
        $amount = $request->get('amount');
        $orderInfo = $request->get('orderInfo', '');

        return view('electro.payment-mock', [
            'orderId' => $orderId,
            'amount' => $amount,
            'orderInfo' => $orderInfo,
        ]);
    }

    /**
     * Xử lý mock payment (simulate thanh toán thành công)
     */
    public function momoMockProcess(Request $request)
    {
        $orderId = $request->get('orderId');
        $action = $request->get('action', 'success'); // 'success' hoặc 'cancel'

        // Parse orderId để lấy original order ID
        $parts = explode('_', $orderId);
        $originalOrderId = $parts[0] ?? null;

        if (!$originalOrderId) {
            return redirect()->route('client.checkout')
                ->with('error', 'Không tìm thấy thông tin đơn hàng.');
        }

        $order = Order::find($originalOrderId);

        if (!$order) {
            return redirect()->route('client.checkout')
                ->with('error', 'Đơn hàng không tồn tại.');
        }

        if ($action === 'success') {
            // Simulate payment success
            $mockData = [
                'resultCode' => 0,
                'message' => 'Thành công',
                'orderId' => $orderId,
                'transId' => 'MOCK_' . time(),
                'amount' => $order->total,
                'orderInfo' => "Thanh toán đơn hàng #{$order->id}",
                'partnerCode' => config('momo.partner_code', 'MOCK'),
                'accessKey' => config('momo.access_key', 'MOCK'),
                'orderType' => 'momo_wallet',
                'payType' => 'webApp',
                'responseTime' => now()->timestamp * 1000,
                'extraData' => '',
                'requestId' => time() . '',
                'signature' => 'mock_signature',
            ];

            // Cập nhật đơn hàng
            if ($order->payment_status !== 'paid') {
                DB::transaction(function () use ($order, $mockData) {
                    $order->update([
                        'payment_status' => 'paid',
                        'transaction_id' => $mockData['transId'],
                        'paid_at' => now(),
                    ]);
                });

                // Gửi email xác nhận
                if ($order->shipping_email) {
                    try {
                        Notification::route('mail', $order->shipping_email)
                            ->notify(new OrderConfirmationNotification($order));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                    }
                }
            }

            return redirect()->route('client.checkout.success', $order)
                ->with('success', 'Thanh toán thành công! (Mock Mode)');
        } else {
            // User hủy thanh toán
            return redirect()->route('client.checkout.success', $order)
                ->with('error', 'Bạn đã hủy thanh toán.');
        }
    }

    /**
     * Xử lý callback return từ MoMo (khi user quay lại từ MoMo)
     */
    public function momoReturn(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('MoMo return callback', $data);

            // Lấy orderId từ request (format: order_id_timestamp)
            $orderId = $data['orderId'] ?? '';
            $parts = explode('_', $orderId);
            $originalOrderId = $parts[0] ?? null;

            if (!$originalOrderId) {
                return redirect()->route('client.checkout')
                    ->with('error', 'Không tìm thấy thông tin đơn hàng.');
            }

            $order = Order::find($originalOrderId);

            if (!$order) {
                return redirect()->route('client.checkout')
                    ->with('error', 'Đơn hàng không tồn tại.');
            }

            // Xác thực và parse kết quả
            $result = $this->momoService->parsePaymentResult($data);

            if (!$result['valid']) {
                return redirect()->route('client.checkout.success', $order)
                    ->with('error', 'Xác thực thanh toán thất bại.');
            }

            if ($result['success']) {
                // Cập nhật đơn hàng nếu chưa được cập nhật
                if ($order->payment_status !== 'paid') {
                    DB::transaction(function () use ($order, $result) {
                        $order->update([
                            'payment_status' => 'paid',
                            'transaction_id' => $result['transaction_id'],
                            'paid_at' => now(),
                        ]);
                    });

                    // Gửi email xác nhận
                    if ($order->shipping_email) {
                        try {
                            Notification::route('mail', $order->shipping_email)
                                ->notify(new OrderConfirmationNotification($order));
                        } catch (\Exception $e) {
                            Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                        }
                    }
                }

                return redirect()->route('client.checkout.success', $order)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                return redirect()->route('client.checkout.success', $order)
                    ->with('error', 'Thanh toán thất bại: ' . ($result['message'] ?? 'Lỗi không xác định'));
            }
        } catch (\Exception $e) {
            Log::error('MoMo return error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('client.checkout')
                ->with('error', 'Có lỗi xảy ra khi xử lý thanh toán.');
        }
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     * Đây là webhook được MoMo gọi để thông báo kết quả thanh toán
     */
    public function momoNotify(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('MoMo IPN callback', $data);

            // Xác thực và parse kết quả
            $result = $this->momoService->parsePaymentResult($data);

            if (!$result['valid']) {
                Log::warning('MoMo IPN: Invalid signature', $data);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Invalid signature',
                ], 400);
            }

            // Lấy orderId từ request (format: order_id_timestamp)
            $orderId = $result['order_id'] ?? '';
            $parts = explode('_', $orderId);
            $originalOrderId = $parts[0] ?? null;

            if (!$originalOrderId) {
                Log::warning('MoMo IPN: Order ID not found', $data);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Order ID not found',
                ], 400);
            }

            $order = Order::find($originalOrderId);

            if (!$order) {
                Log::warning('MoMo IPN: Order not found', ['order_id' => $originalOrderId]);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Order not found',
                ], 404);
            }

            // Cập nhật đơn hàng nếu thanh toán thành công
            if ($result['success'] && $order->payment_status !== 'paid') {
                DB::transaction(function () use ($order, $result) {
                    $order->update([
                        'payment_status' => 'paid',
                        'transaction_id' => $result['transaction_id'],
                        'paid_at' => now(),
                    ]);
                });

                // Gửi email xác nhận
                if ($order->shipping_email) {
                    try {
                        Notification::route('mail', $order->shipping_email)
                            ->notify(new OrderConfirmationNotification($order));
                    } catch (\Exception $e) {
                        Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                    }
                }

                Log::info('MoMo IPN: Order updated successfully', [
                    'order_id' => $order->id,
                    'transaction_id' => $result['transaction_id'],
                ]);
            }

            // Trả về response cho MoMo
            return response()->json([
                'resultCode' => 0,
                'message' => 'Success',
            ], 200);
        } catch (\Exception $e) {
            Log::error('MoMo IPN error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'resultCode' => -1,
                'message' => 'Internal error',
            ], 500);
        }
    }
}

