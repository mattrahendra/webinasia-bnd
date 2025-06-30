<?php

// app/Services/MidtransService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $clientKey;
    protected $baseUrl;
    protected $isSandbox;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isSandbox = config('services.midtrans.sandbox', true);
        $this->baseUrl = $this->isSandbox
            ? 'https://api.sandbox.midtrans.com/v2'
            : 'https://api.midtrans.com/v2';
    }

    /**
     * Create payment transaction
     */
    public function createTransaction(array $orderData, string $paymentMethod): array
    {
        try {
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderData['order_id'],
                    'gross_amount' => (int) $orderData['gross_amount']
                ],
                'customer_details' => [
                    'first_name' => $orderData['customer_details']['name'],
                    'email' => $orderData['customer_details']['email'],
                    'phone' => $orderData['customer_details']['phone'],
                    'billing_address' => [
                        'address' => $orderData['customer_details']['address'],
                        'city' => $orderData['customer_details']['city'],
                        'postal_code' => $orderData['customer_details']['postal_code'],
                        'country_code' => $orderData['customer_details']['country']
                    ]
                ],
                'item_details' => $orderData['item_details'],
                'callbacks' => [
                    'finish' => config('app.url') . '/payment/success',
                    'error' => config('app.url') . '/payment/error',
                    'pending' => config('app.url') . '/payment/pending'
                ]
            ];

            // Add payment method specific settings
            $payload = $this->addPaymentMethodSettings($payload, $paymentMethod);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->post($this->baseUrl . '/charge', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => [
                        'transaction_id' => $data['transaction_id'],
                        'order_id' => $data['order_id'],
                        'token' => $data['token'] ?? null,
                        'redirect_url' => $data['redirect_url'] ?? null,
                        'payment_type' => $data['payment_type'],
                        'transaction_status' => $data['transaction_status'],
                        'actions' => $data['actions'] ?? null
                    ]
                ];
            } else {
                $error = $response->json();
                return [
                    'success' => false,
                    'message' => $error['error_messages'][0] ?? 'Payment creation failed'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Midtrans create transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable'
            ];
        }
    }

    /**
     * Add payment method specific settings
     */
    private function addPaymentMethodSettings(array $payload, string $paymentMethod): array
    {
        switch ($paymentMethod) {
            case 'credit_card':
                $payload['credit_card'] = [
                    'secure' => true,
                    'save_card' => false
                ];
                break;

            case 'bank_transfer':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = [
                    'bank' => 'permata' // Default to Permata VA
                ];
                break;

            case 'e_wallet':
                $payload['payment_type'] = 'gopay';
                break;

            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris'] = [
                    'acquirer' => 'gopay'
                ];
                break;

            default:
                // Default to credit card
                $payload['credit_card'] = [
                    'secure' => true,
                    'save_card' => false
                ];
        }

        return $payload;
    }

    /**
     * Verify notification signature
     */
    public function verifyNotification(array $notification): bool
    {
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $serverKey = $this->serverKey;

        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($signatureKey, $notification['signature_key']);
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->get($this->baseUrl . '/' . $orderId . '/status');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to get transaction status'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Midtrans get status error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable'
            ];
        }
    }

    /**
     * Cancel transaction
     */
    public function cancelTransaction(string $orderId): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->post($this->baseUrl . '/' . $orderId . '/cancel');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to cancel transaction'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Midtrans cancel transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable'
            ];
        }
    }
}
