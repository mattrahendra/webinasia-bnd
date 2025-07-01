<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;
    protected $goDaddyService;

    public function __construct(MidtransService $midtransService, GoDaddyService $goDaddyService)
    {
        $this->goDaddyService = $goDaddyService;
        $this->midtransService = $midtransService;
        $this->middleware('role:user,admin')->except(['callback']);
    }

    public function process(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|string|in:credit_card,bank_transfer,e_wallet,qris'
        ]);

        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'Order cannot be processed');
        }

        DB::beginTransaction();
        try {
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $order->total_price,
                'status' => 'pending'
            ]);

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

                $paymentUrl = $midtransResponse['data']['redirect_url'] ?? null;
                if ($paymentUrl) {
                    return redirect()->away($paymentUrl);
                }

                return redirect()->route('orders.show', $order)->with('success', 'Payment created successfully');
            } else {
                throw new \Exception('Failed to create payment: ' . $midtransResponse['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->route('orders.show', $order)->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $notification = $request->all();

        try {
            $verifiedNotification = $this->midtransService->verifyNotification($notification);

            if (!$verifiedNotification) {
                return response()->json(['message' => 'Invalid notification'], 400);
            }

            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? 'accept';

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

            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept') {
                    $payment->update([
                        'status' => 'success',
                        'paid_at' => now(),
                        'payment_data' => array_merge($payment->payment_data ?? [], $notification)
                    ]);

                    $order->update(['status' => 'paid']);
                    $this->processDomainRegistration($order);
                }
            } elseif ($transactionStatus == 'pending') {
                $payment->update(['status' => 'pending']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'cancel' || $transactionStatus == 'expire') {
                $payment->update(['status' => 'failed']);
                $order->update(['status' => 'cancelled']);
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

    private function processDomainRegistration(Order $order): void
    {
        try {
            $fullDomain = $order->domain_name . '.' . $order->domain_extension;
            $result = $this->goDaddyService->purchaseDomain($fullDomain, $order->customer_data);

            if ($result['success']) {
                $order->update(['status' => 'processing']);
                Log::info('Domain registration successful for order: ' . $order->order_number);
            } else {
                Log::error('Domain registration failed for order: ' . $order->order_number . ' - ' . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Domain registration exception: ' . $e->getMessage());
        }
    }

    private function releaseDomainReservation(Order $order): void
    {
        $fullDomain = $order->domain_name . '.' . $order->domain_extension;

        Domain::where('name', $fullDomain)
            ->where('user_id', $order->user_id)
            ->update([
                'status' => 'available',
                'user_id' => null,
                'reserved_until' => null
            ]);
    }
}
