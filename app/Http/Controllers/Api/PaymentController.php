<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;
    protected $goDaddyService;

    public function __construct(MidtransService $midtransService, GoDaddyService $goDaddyService)
    {
        $this->midtransService = $midtransService;
        $this->goDaddyService = $goDaddyService;
    }

    /**
     * Process payment
     */
    public function process(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|string|in:credit_card,bank_transfer,e_wallet,qris'
        ]);

        $user = Auth::user();

        // Check if order belongs to user
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Check if order is still pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be processed'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $order->total_price,
                'status' => 'pending'
            ]);

            // Create payment with Midtrans
            $paymentData = [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_price,
                'customer_details' => $order->customer_data,
                'item_details' => [
                    [
                        'id' => 'template_' . $order->template_id,
                        'price' => $order->template_price,
                        'quantity' => 1,
                        'name' => $order->template->name ?? 'Website Template'
                    ],
                    [
                        'id' => 'domain_' . $order->domain_name,
                        'price' => $order->domain_price,
                        'quantity' => 1,
                        'name' => 'Domain: ' . $order->domain_name . '.' . $order->domain_extension
                    ]
                ]
            ];

            $midtransResponse = $this->midtransService->createTransaction($paymentData, $request->payment_method);

            if ($midtransResponse['success']) {
                $payment->update([
                    'transaction_id' => $midtransResponse['data']['transaction_id'],
                    'external_id' => $midtransResponse['data']['order_id'],
                    'payment_data' => $midtransResponse['data']
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'payment' => $payment,
                        'payment_url' => $midtransResponse['data']['redirect_url'] ?? null,
                        'payment_token' => $midtransResponse['data']['token'] ?? null
                    ],
                    'message' => 'Payment created successfully'
                ]);
            } else {
                throw new \Exception('Failed to create payment: ' . $midtransResponse['message']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment callback/webhook
     */
    public function callback(Request $request): JsonResponse
    {
        $notification = $request->all();

        try {
            // Verify notification from Midtrans
            $verifiedNotification = $this->midtransService->verifyNotification($notification);

            if (!$verifiedNotification) {
                return response()->json(['message' => 'Invalid notification'], 400);
            }

            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? 'accept';

            // Find order and payment
            $order = Order::where('order_number', $orderId)->first();
            if (!$order) {
                Log::error('Order not found for callback: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = Payment::where('order_id', $order->id)->first();
            if (!$payment) {
                Log::error('Payment not found for order: ' . $orderId);
                return response()->json(['message' => 'Payment not found'], 404);
            }

            DB::beginTransaction();

            // Update payment status based on Midtrans notification
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept') {
                    // Payment successful
                    $payment->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'payment_data' => array_merge($payment->payment_data ?? [], $notification)
                    ]);

                    $order->update(['status' => 'paid']);

                    // Process domain registration
                    $this->processDomainRegistration($order);
                }
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'cancelled']);

                // Release domain reservation
                $this->releaseDomainReservation($order);
            }

            DB::commit();

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Callback processing failed'], 500);
        }
    }

    /**
     * Process domain registration after payment success
     */
    private function processDomainRegistration(Order $order): void
    {
        try {
            $fullDomain = $order->domain_name . '.' . $order->domain_extension;

            // Register domain with GoDaddy
            $result = $this->goDaddyService->purchaseDomain($fullDomain, $order->customer_data);

            if ($result['success']) {
                $order->update(['status' => 'processing']);
                Log::info('Domain registration successful for order: ' . $order->order_number);
            } else {
                Log::error('Domain registration failed for order: ' . $order->order_number . ' - ' . ($result['error'] ?? 'Unknown error'));
                // Could implement retry logic or manual processing flag
            }
        } catch (\Exception $e) {
            Log::error('Domain registration exception: ' . $e->getMessage());
        }
    }

    /**
     * Release domain reservation
     */
    private function releaseDomainReservation(Order $order): void
    {
        $fullDomain = $order->domain_name . '.' . $order->domain_extension;

        \App\Models\Domain::where('name', $fullDomain)
            ->where('user_id', $order->user_id)
            ->update([
                'status' => 'available',
                'user_id' => null,
                'reserved_until' => null
            ]);
    }
}
