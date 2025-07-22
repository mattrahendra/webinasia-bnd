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
    protected $snapUrl;
    protected $isSandbox;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->clientKey = config('services.midtrans.client_key');
        $this->isSandbox = config('services.midtrans.sandbox', true);

        // Core API URLs
        $this->baseUrl = $this->isSandbox
            ? 'https://api.sandbox.midtrans.com/v2'
            : 'https://api.midtrans.com/v2';

        // Snap API URLs
        $this->snapUrl = $this->isSandbox
            ? 'https://app.sandbox.midtrans.com/snap/v1'
            : 'https://app.midtrans.com/snap/v1';
    }

    /**
     * Create Snap payment transaction (recommended for sandbox)
     */
    public function createSnapTransaction(array $orderData): array
    {
        try {
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderData['order_id'],
                    'gross_amount' => (int) $orderData['gross_amount']
                ],
                'customer_details' => [
                    'first_name' => $orderData['customer_details']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                    'last_name' => $orderData['customer_details']['last_name'] ?? '',
                    'email' => $orderData['customer_details']['email'],
                    'phone' => $orderData['customer_details']['phone'],
                    'billing_address' => [
                        'first_name' => $orderData['customer_details']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                        'last_name' => $orderData['customer_details']['last_name'] ?? '',
                        'address' => $orderData['customer_details']['billing_address']['address'] ?? 'Jl. Raya Sandbox No. 123',
                        'city' => $orderData['customer_details']['billing_address']['city'] ?? 'Jakarta',
                        'postal_code' => $orderData['customer_details']['billing_address']['postal_code'] ?? '12345',
                        'country_code' => $orderData['customer_details']['billing_address']['country_code'] ?? 'IDN'
                    ]
                ],
                'item_details' => $orderData['item_details'],
                'callbacks' => [
                    'finish' => config('app.url') . '/payment/success',
                    'error' => config('app.url') . '/payment/error',
                    'pending' => config('app.url') . '/payment/pending'
                ]
            ];

            // Add shipping address if provided
            if (isset($orderData['customer_details']['shipping_address'])) {
                $payload['customer_details']['shipping_address'] = [
                    'first_name' => $orderData['customer_details']['shipping_address']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                    'last_name' => $orderData['customer_details']['shipping_address']['last_name'] ?? '',
                    'address' => $orderData['customer_details']['shipping_address']['address'] ?? 'Jl. Raya Sandbox No. 123',
                    'city' => $orderData['customer_details']['shipping_address']['city'] ?? 'Jakarta',
                    'postal_code' => $orderData['customer_details']['shipping_address']['postal_code'] ?? '12345',
                    'country_code' => $orderData['customer_details']['shipping_address']['country_code'] ?? 'IDN'
                ];
            }

            // Enhanced settings for sandbox testing
            $payload['enabled_payments'] = [
                'credit_card', 'gopay', 'shopeepay', 'qris',
                'bca_va', 'bni_va', 'bri_va', 'permata_va',
                'other_va', 'indomaret', 'alfamart'
            ];

            // Credit card settings for sandbox
            $payload['credit_card'] = [
                'secure' => true,
                'save_card' => false,
                'channel' => 'migs',
                'bank' => 'bca', // For sandbox testing
                'installment' => [
                    'required' => false,
                    'terms' => [
                        'bni' => [3, 6, 12],
                        'mandiri' => [3, 6, 12],
                        'cimb' => [3],
                        'bca' => [3, 6, 12],
                        'offline' => [6, 12]
                    ]
                ]
            ];

            // Add expiry settings
            $payload['expiry'] = [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60 // 1 hour expiry for sandbox
            ];

            // Log the payload for debugging
            Log::info('Midtrans Snap payload:', $payload);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':')
            ])->post($this->snapUrl . '/transactions', $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => [
                        'token' => $data['token'],
                        'redirect_url' => $data['redirect_url'],
                        'order_id' => $orderData['order_id'],
                        'payment_type' => 'snap',
                        'transaction_status' => 'pending',
                        'snap_url' => $this->isSandbox
                            ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $data['token']
                            : 'https://app.midtrans.com/snap/v2/vtweb/' . $data['token']
                    ]
                ];
            } else {
                $error = $response->json();
                Log::error('Midtrans Snap API error:', $error);
                return [
                    'success' => false,
                    'message' => $error['error_messages'][0] ?? 'Payment creation failed'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Midtrans create snap transaction error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Service temporarily unavailable'
            ];
        }
    }

    /**
     * Create payment transaction (legacy method for specific payment types)
     */
    public function createTransaction(array $orderData, string $paymentMethod): array
    {
        // For sandbox, prefer Snap for better testing experience
        if ($this->isSandbox && in_array($paymentMethod, ['credit_card', 'mixed'])) {
            return $this->createSnapTransaction($orderData);
        }

        try {
            $payload = [
                'transaction_details' => [
                    'order_id' => $orderData['order_id'],
                    'gross_amount' => (int) $orderData['gross_amount']
                ],
                'customer_details' => [
                    'first_name' => $orderData['customer_details']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                    'last_name' => $orderData['customer_details']['last_name'] ?? '',
                    'email' => $orderData['customer_details']['email'],
                    'phone' => $orderData['customer_details']['phone'],
                    'billing_address' => [
                        'first_name' => $orderData['customer_details']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                        'last_name' => $orderData['customer_details']['last_name'] ?? '',
                        'address' => $orderData['customer_details']['billing_address']['address'] ?? 'Jl. Raya Sandbox No. 123',
                        'city' => $orderData['customer_details']['billing_address']['city'] ?? 'Jakarta',
                        'postal_code' => $orderData['customer_details']['billing_address']['postal_code'] ?? '12345',
                        'country_code' => $orderData['customer_details']['billing_address']['country_code'] ?? 'IDN'
                    ]
                ],
                'item_details' => $orderData['item_details'],
                'callbacks' => [
                    'finish' => config('app.url') . '/payment/success',
                    'error' => config('app.url') . '/payment/error',
                    'pending' => config('app.url') . '/payment/pending'
                ]
            ];

            // Add shipping address if provided
            if (isset($orderData['customer_details']['shipping_address'])) {
                $payload['customer_details']['shipping_address'] = [
                    'first_name' => $orderData['customer_details']['shipping_address']['first_name'] ?? $orderData['customer_details']['name'] ?? '',
                    'last_name' => $orderData['customer_details']['shipping_address']['last_name'] ?? '',
                    'address' => $orderData['customer_details']['shipping_address']['address'] ?? 'Jl. Raya Sandbox No. 123',
                    'city' => $orderData['customer_details']['shipping_address']['city'] ?? 'Jakarta',
                    'postal_code' => $orderData['customer_details']['shipping_address']['postal_code'] ?? '12345',
                    'country_code' => $orderData['customer_details']['shipping_address']['country_code'] ?? 'IDN'
                ];
            }

            // Add payment method specific settings
            $payload = $this->addPaymentMethodSettings($payload, $paymentMethod);

            // Log the payload for debugging
            Log::info('Midtrans payload:', $payload);

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
                        'actions' => $data['actions'] ?? null,
                        'va_numbers' => $data['va_numbers'] ?? null,
                        'bill_key' => $data['bill_key'] ?? null,
                        'biller_code' => $data['biller_code'] ?? null
                    ]
                ];
            } else {
                $error = $response->json();
                Log::error('Midtrans API error:', $error);
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
     * Add payment method specific settings with sandbox optimizations
     */
    private function addPaymentMethodSettings(array $payload, string $paymentMethod): array
    {
        switch ($paymentMethod) {
            case 'credit_card':
                $payload['payment_type'] = 'credit_card';
                $payload['credit_card'] = [
                    'secure' => true,
                    'save_card' => false,
                    'channel' => 'migs'
                ];
                break;

            case 'bank_transfer':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = [
                    'bank' => 'bca' // BCA VA for better sandbox support
                ];
                break;

            case 'bca_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bca'];
                break;

            case 'bni_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bni'];
                break;

            case 'bri_va':
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = ['bank' => 'bri'];
                break;

            case 'gopay':
                $payload['payment_type'] = 'gopay';
                $payload['gopay'] = [
                    'enable_callback' => true,
                    'callback_url' => config('app.url') . '/payment/gopay-callback'
                ];
                break;

            case 'shopeepay':
                $payload['payment_type'] = 'shopeepay';
                $payload['shopeepay'] = [
                    'callback_url' => config('app.url') . '/payment/shopeepay-callback'
                ];
                break;

            case 'qris':
                $payload['payment_type'] = 'qris';
                $payload['qris'] = [
                    'acquirer' => 'gopay'
                ];
                break;

            case 'indomaret':
                $payload['payment_type'] = 'cstore';
                $payload['cstore'] = [
                    'store' => 'indomaret',
                    'message' => 'Payment for Order #' . $payload['transaction_details']['order_id']
                ];
                break;

            case 'alfamart':
                $payload['payment_type'] = 'cstore';
                $payload['cstore'] = [
                    'store' => 'alfamart',
                    'message' => 'Payment for Order #' . $payload['transaction_details']['order_id']
                ];
                break;

            default:
                // Default to Snap for mixed payment options
                return $this->createSnapTransaction([
                    'order_id' => $payload['transaction_details']['order_id'],
                    'gross_amount' => $payload['transaction_details']['gross_amount'],
                    'customer_details' => $payload['customer_details'],
                    'item_details' => $payload['item_details']
                ]);
        }

        return $payload;
    }

    /**
     * Get sandbox testing instructions
     */
    public function getSandboxInstructions(string $paymentType): array
    {
        $instructions = [
            'credit_card' => [
                'title' => 'Test Credit Card Numbers',
                'cards' => [
                    'visa_success' => '4811111111111114',
                    'visa_failure' => '4911111111111113',
                    'mastercard_success' => '5211111111111117',
                    'mastercard_failure' => '5311111111111115'
                ],
                'cvv' => '123',
                'expiry' => '12/25',
                'otp' => '112233'
            ],
            'gopay' => [
                'title' => 'GoPay Sandbox Testing',
                'phone' => '081234567890',
                'pin' => '123456'
            ],
            'bank_transfer' => [
                'title' => 'Bank Transfer Testing',
                'note' => 'Use the provided VA number to simulate payment via your bank\'s mobile app or ATM'
            ],
            'qris' => [
                'title' => 'QRIS Testing',
                'note' => 'Scan the QR code with any e-wallet app to simulate payment'
            ]
        ];

        return $instructions[$paymentType] ?? [];
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

    /**
     * Get client key for frontend integration
     */
    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    /**
     * Get snap URL for frontend integration
     */
    public function getSnapUrl(): string
    {
        return $this->isSandbox
            ? 'https://app.sandbox.midtrans.com/snap/snap.js'
            : 'https://app.midtrans.com/snap/snap.js';
    }
}
