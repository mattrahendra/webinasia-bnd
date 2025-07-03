<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Template;
use App\Services\MidtransService;
use Illuminate\Http\Request;

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
            'payment_method' => 'required|string|in:bank_transfer,credit_card,e_wallet,qris',
            'promo_code' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
        ]);

        $template = Template::findOrFail($validated['template_id']);
        $templatePrice = $template->price;
        $domainPrice = $validated['domain_price'] * $validated['years'];
        $totalPrice = $templatePrice + $domainPrice;

        if ($validated['promo_code'] === 'DISCOUNT10') {
            $totalPrice *= 0.9; // Diskon 10%
        }

        $order = Order::create([
            'order_number' => 'ORD-' . time(),
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
            ],
        ]);

        $midtransService = new MidtransService();
        $orderData = [
            'order_id' => $order->order_number,
            'gross_amount' => $totalPrice,
            'customer_details' => [
                'first_name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'billing_address' => [
                    'first_name' => $validated['name'],
                    'address' => $validated['address'] ?? 'No address provided',
                    'city' => $validated['city'] ?? 'Unknown',
                    'postal_code' => $validated['postal_code'] ?? '00000',
                    'country_code' => 'IDN',
                ],
                'shipping_address' => [
                    'first_name' => $validated['name'],
                    'address' => $validated['address'] ?? 'No address provided',
                    'city' => $validated['city'] ?? 'Unknown',
                    'postal_code' => $validated['postal_code'] ?? '00000',
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => [
                [
                    'id' => 'template_' . $template->id,
                    'price' => $templatePrice,
                    'quantity' => 1,
                    'name' => 'Template: ' . $template->name,
                ],
                [
                    'id' => 'domain_' . $validated['domain_name'] . '.' . $validated['extension'],
                    'price' => $validated['domain_price'],
                    'quantity' => $validated['years'],
                    'name' => 'Domain: ' . $validated['domain_name'] . '.' . $validated['extension'],
                ],
            ],
        ];

        $paymentMethod = $validated['payment_method'];
        $result = $midtransService->createTransaction($orderData, $paymentMethod);

        if ($result['success']) {
            $payment = $order->payments()->create([
                'transaction_id' => $result['data']['transaction_id'],
                'payment_method' => $paymentMethod,
                'payment_type' => $result['data']['payment_type'],
                'status' => $result['data']['transaction_status'],
                'amount' => $totalPrice,
                'payment_data' => $result['data'],
            ]);

            // Handle different payment types
            if (!empty($result['data']['redirect_url'])) {
                // For payment methods that have redirect URLs (credit card, some e-wallets)
                return redirect()->away($result['data']['redirect_url']);
            } else {
                // For payment methods without redirect URLs (QRIS, bank transfer)
                return redirect()->route('orders.payment', $order->id)
                    ->with('success', 'Payment created successfully')
                    ->with('payment_data', $result['data']);
            }
        }

        return back()->with('error', $result['message']);
    }

    public function showPayment(Order $order)
    {
        $payment = $order->payments()->latest()->first();

        if (!$payment) {
            return redirect()->route('orders.index')->with('error', 'Payment not found');
        }

        return view('orders.payment', compact('order', 'payment'));
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
}
