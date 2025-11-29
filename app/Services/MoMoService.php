<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoMoService
{
    protected $partnerCode;
    protected $accessKey;
    protected $secretKey;
    protected $endpoint;
    protected $returnUrl;
    protected $notifyUrl;
    protected $requestType;

    protected $isMockMode = false;

    public function __construct()
    {
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey = config('momo.access_key');
        $this->secretKey = config('momo.secret_key');
        $environment = config('momo.environment', 'mock');
        $this->isMockMode = ($environment === 'mock');
        $this->endpoint = config("momo.endpoints.{$environment}");
        $this->returnUrl = url(config('momo.return_url'));
        $this->notifyUrl = url(config('momo.notify_url'));
        $this->requestType = config('momo.request_type', 'captureWallet');
    }

    /**
     * Tạo payment request với MoMo
     *
     * @param string $orderId Mã đơn hàng
     * @param float $amount Số tiền thanh toán
     * @param string $orderInfo Thông tin đơn hàng
     * @param string $extraData Dữ liệu bổ sung (JSON string)
     * @return array|null
     */
    public function createPayment(string $orderId, float $amount, string $orderInfo = '', string $extraData = ''): ?array
    {
        try {
            $requestId = time() . '';
            $orderIdWithTime = $orderId . '_' . time();
            $amount = (int) $amount;
            $requestType = $this->requestType;
            $extraData = $extraData ?: '';

            // Nếu là mock mode, tạo mock payment URL
            if ($this->isMockMode) {
                $mockPayUrl = route('client.payment.momo.mock', [
                    'orderId' => $orderIdWithTime,
                    'amount' => $amount,
                    'orderInfo' => $orderInfo,
                ]);
                
                return [
                    'success' => true,
                    'payUrl' => $mockPayUrl,
                    'orderId' => $orderIdWithTime,
                    'requestId' => $requestId,
                ];
            }

            // Tạo signature
            $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$this->notifyUrl}&orderId={$orderIdWithTime}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}&redirectUrl={$this->returnUrl}&requestId={$requestId}&requestType={$requestType}";
            $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

            $data = [
                'partnerCode' => $this->partnerCode,
                'partnerName' => 'Phonezy',
                'storeId' => 'PhonezyStore',
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderIdWithTime,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $this->returnUrl,
                'ipnUrl' => $this->notifyUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature,
            ];

            $response = Http::post($this->endpoint, $data);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['resultCode']) && $result['resultCode'] == 0) {
                    return [
                        'success' => true,
                        'payUrl' => $result['payUrl'],
                        'orderId' => $orderIdWithTime,
                        'requestId' => $requestId,
                    ];
                } else {
                    Log::error('MoMo payment error: ' . ($result['message'] ?? 'Unknown error'), $result);
                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Lỗi không xác định từ MoMo',
                    ];
                }
            }

            Log::error('MoMo HTTP error', ['status' => $response->status(), 'body' => $response->body()]);
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến MoMo',
            ];
        } catch (\Exception $e) {
            Log::error('MoMo service exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return [
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Xác thực signature từ callback của MoMo
     *
     * @param array $data Dữ liệu từ callback
     * @return bool
     */
    public function verifySignature(array $data): bool
    {
        // Trong mock mode, luôn trả về true để test
        if ($this->isMockMode) {
            return true;
        }

        try {
            $signature = $data['signature'] ?? '';
            $accessKey = $data['accessKey'] ?? '';
            $orderId = $data['orderId'] ?? '';
            $partnerCode = $data['partnerCode'] ?? '';
            $amount = $data['amount'] ?? '';
            $orderInfo = $data['orderInfo'] ?? '';
            $orderType = $data['orderType'] ?? '';
            $transId = $data['transId'] ?? '';
            $message = $data['message'] ?? '';
            $localMessage = $data['localMessage'] ?? '';
            $responseTime = $data['responseTime'] ?? '';
            $extraData = $data['extraData'] ?? '';
            $resultCode = $data['resultCode'] ?? '';
            $payType = $data['payType'] ?? '';

            $requestId = $data['requestId'] ?? '';
            $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&message={$message}&orderId={$orderId}&orderInfo={$orderInfo}&orderType={$orderType}&partnerCode={$partnerCode}&payType={$payType}&requestId={$requestId}&responseTime={$responseTime}&resultCode={$resultCode}&transId={$transId}";
            $expectedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);

            return hash_equals($expectedSignature, $signature);
        } catch (\Exception $e) {
            Log::error('MoMo signature verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kiểm tra kết quả thanh toán
     *
     * @param array $data Dữ liệu từ callback
     * @return array
     */
    public function parsePaymentResult(array $data): array
    {
        $isValid = $this->verifySignature($data);

        if (!$isValid) {
            return [
                'success' => false,
                'valid' => false,
                'message' => 'Chữ ký không hợp lệ',
            ];
        }

        $resultCode = (int) ($data['resultCode'] ?? -1);
        $transId = $data['transId'] ?? '';
        $orderId = $data['orderId'] ?? '';
        $amount = (float) ($data['amount'] ?? 0);
        $message = $data['message'] ?? '';

        // resultCode = 0: Thanh toán thành công
        if ($resultCode === 0) {
            return [
                'success' => true,
                'valid' => true,
                'transaction_id' => $transId,
                'order_id' => $orderId,
                'amount' => $amount,
                'message' => $message,
            ];
        }

        return [
            'success' => false,
            'valid' => true,
            'transaction_id' => $transId,
            'order_id' => $orderId,
            'amount' => $amount,
            'message' => $message,
            'result_code' => $resultCode,
        ];
    }
}

