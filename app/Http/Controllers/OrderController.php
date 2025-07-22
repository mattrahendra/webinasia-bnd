<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Template;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use App\Mail\PaymentInvoiceMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function selectDomain(Request $request)
    {
        $validated = $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string',
            'price' => 'required|numeric',
            'full_domain' => 'required|string',
        ]);

        return redirect()->route('orders.create', $validated);
    }

    public function create(Request $request)
    {
        $domainData = $request->only(['domain_name', 'extension', 'price', 'full_domain']);
        $templates = Template::all();

        return view('orders.create', compact('domainData', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string',
            'domain_price' => 'required|numeric',
            'template_id' => 'required|exists:templates,id',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'years' => 'required|integer|min:1|max:5',
            'payment_method' => 'required|string|in:snap,bank_transfer,bca_va,bni_va,bri_va,credit_card,gopay,shopeepay,qris,indomaret,alfamart',
            'promo_code' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $template = Template::findOrFail($validated['template_id']);
        $templatePrice = $template->price;
        $domainPrice = $validated['domain_price'] * $validated['years'];
        $totalPrice = $templatePrice + $domainPrice;

        // Apply promo code
        if ($validated['promo_code'] === 'DISCOUNT10') {
            $totalPrice *= 0.9; // Diskon 10%
        }

        $order = Order::create([
            'order_number' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'template_id' => $validated['template_id'],
            'domain_name' => $validated['domain_name'],
            'domain_extension' => $validated['extension'],
            'template_price' => $templatePrice,
            'domain_price' => $validated['domain_price'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'customer_data' => [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
            ],
        ]);

        $midtransService = new MidtransService();

        // Prepare order data for Midtrans
        $orderData = [
            'order_id' => $order->order_number,
            'gross_amount' => $totalPrice,
            'customer_details' => [
                'first_name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'billing_address' => [
                    'first_name' => $validated['name'],
                    'address' => $validated['address'] ?: 'Jl. Raya Sandbox No. 123',
                    'city' => $validated['city'] ?: 'Jakarta',
                    'postal_code' => $validated['postal_code'] ?: '12345',
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name' => $validated['name'],
                    'address' => $validated['address'] ?: 'Jl. Raya Sandbox No. 123',
                    'city' => $validated['city'] ?: 'Jakarta',
                    'postal_code' => $validated['postal_code'] ?: '12345',
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => [
                [
                    'id' => 'template_' . $template->id,
                    'price' => (int) $templatePrice,
                    'quantity' => 1,
                    'name' => 'Template: ' . $template->name,
                ],
                [
                    'id' => 'domain_' . $validated['domain_name'] . '.' . $validated['extension'],
                    'price' => (int) $validated['domain_price'],
                    'quantity' => $validated['years'],
                    'name' => 'Domain: ' . $validated['domain_name'] . '.' . $validated['extension'] . ' (' . $validated['years'] . ' year' . ($validated['years'] > 1 ? 's' : '') . ')',
                ],
            ],
        ];

        // Add discount item if promo code is applied
        if ($validated['promo_code'] === 'DISCOUNT10') {
            $discountAmount = ($templatePrice + $domainPrice) * 0.1;
            $orderData['item_details'][] = [
                'id' => 'discount_' . $validated['promo_code'],
                'price' => -(int) $discountAmount,
                'quantity' => 1,
                'name' => 'Discount: ' . $validated['promo_code'] . ' (10%)',
            ];
        }

        $paymentMethod = $validated['payment_method'];

        // Use Snap for mixed payment options or when payment method is 'snap'
        if ($paymentMethod === 'snap' || $paymentMethod === 'mixed') {
            $result = $midtransService->createSnapTransaction($orderData);
        } else {
            $result = $midtransService->createTransaction($orderData, $paymentMethod);
        }

        if ($result['success']) {
            $payment = $order->payments()->create([
                'transaction_id' => $result['data']['transaction_id'] ?? $order->order_number,
                'payment_method' => $paymentMethod,
                'payment_type' => $result['data']['payment_type'],
                'status' => $result['data']['transaction_status'],
                'amount' => $totalPrice,
                'payment_data' => $result['data'],
            ]);

            // Handle different response types
            if (!empty($result['data']['redirect_url'])) {
                // For Snap and other redirect-based payments
                return redirect()->route('orders.payment', $order->id)
                    ->with('success', 'Payment created successfully')
                    ->with('payment_data', $result['data'])
                    ->with('redirect_url', $result['data']['redirect_url']);
            } else {
                // For direct payment methods (VA, QRIS, etc.)
                return redirect()->route('orders.payment', $order->id)
                    ->with('success', 'Payment created successfully')
                    ->with('payment_data', $result['data']);
            }
        }

        return back()->with('error', $result['message'])->withInput();
    }

    public function showPayment(Order $order)
    {
        $payment = $order->payments()->latest()->first();

        if (!$payment) {
            return redirect()->route('orders.index')->with('error', 'Payment not found');
        }

        $midtransService = new MidtransService();
        $sandboxInstructions = $midtransService->getSandboxInstructions($payment->payment_type);

        return view('orders.payment', compact('order', 'payment', 'sandboxInstructions'));
    }

    public function checkPaymentStatus(Order $order)
    {
        $midtransService = new MidtransService();
        $result = $midtransService->getTransactionStatus($order->order_number);

        if ($result['success']) {
            $transactionData = $result['data'];

            // Update payment status
            $payment = $order->payments()->latest()->first();
            if ($payment) {
                $payment->update([
                    'status' => $transactionData['transaction_status'],
                    'payment_data' => array_merge($payment->payment_data, $transactionData)
                ]);
            }

            // Update order status based on payment status
            switch ($transactionData['transaction_status']) {
                case 'settlement':
                case 'capture':
                    $order->update(['status' => 'paid']);
                    break;
                case 'pending':
                    $order->update(['status' => 'pending']);
                    break;
                case 'deny':
                case 'cancel':
                case 'expire':
                case 'failure':
                    $order->update(['status' => 'cancelled']);
                    break;
            }

            return response()->json([
                'success' => true,
                'status' => $transactionData['transaction_status'],
                'order_status' => $order->fresh()->status
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->get('order_id');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                // Check payment status from Midtrans
                $this->checkPaymentStatus($order);
                return redirect()->route('orders.payment', $order->id)
                    ->with('success', 'Payment completed successfully!');
            }
        }

        return redirect()->route('home')->with('success', 'Payment completed successfully!');
    }

    public function paymentError(Request $request)
    {
        $orderId = $request->get('order_id');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                return redirect()->route('orders.payment', $order->id)
                    ->with('error', 'Payment failed or was cancelled.');
            }
        }

        return redirect()->route('home')->with('error', 'Payment failed or was cancelled.');
    }

    public function paymentPending(Request $request)
    {
        $orderId = $request->get('order_id');

        if ($orderId) {
            $order = Order::where('order_number', $orderId)->first();
            if ($order) {
                return redirect()->route('orders.payment', $order->id)
                    ->with('info', 'Payment is pending. Please complete your payment.');
            }
        }

        return redirect()->route('home')->with('info', 'Payment is pending.');
    }

    public function index(Request $request)
    {
        $query = Order::with(['template', 'payments'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number or domain name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('domain_name', 'like', "%{$search}%")
                    ->orWhereJsonContains('customer_data->name', $search)
                    ->orWhereJsonContains('customer_data->email', $search);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15);

        // Get summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'paid_orders' => Order::where('status', 'paid')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'paid')->sum('total_price'),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    // Method untuk manual payment (simulasi pembayaran berhasil)
public function manualPayment(Order $order)
{
    $payment = $order->payments()->latest()->first();

    if (!$payment) {
        return response()->json([
            'success' => false,
            'message' => 'Payment not found'
        ]);
    }

    // Update payment status
    $payment->update([
        'status' => 'success',
        'paid_at' => now(),
        'payment_data' => array_merge($payment->payment_data, [
            'transaction_status' => 'success',
            'settlement_time' => now()->toISOString(),
            'manual_payment' => true
        ])
    ]);

    // Update order status
    $order->update(['status' => 'paid']);

    try {
        // Send invoice email
        Mail::to($order->customer_data['email'])
            ->send(new PaymentInvoiceMail($order, $payment));

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully. Invoice has been sent to your email.',
            'order_status' => 'paid',
            'payment_status' => 'settlement'
        ]);
    } catch (\Exception $e) {
        // Log error but don't fail the payment
        \Log::error('Failed to send invoice email: ' . $e->getMessage());

        return response()->json([
            'success' => true,
            'message' => 'Payment confirmed successfully. There was an issue sending the invoice email, but your payment has been processed.',
            'order_status' => 'paid',
            'payment_status' => 'settlement'
        ]);
    }
}
}
