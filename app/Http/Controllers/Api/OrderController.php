<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Template;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Create new order
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'domain_name' => 'required|string',
            'domain_extension' => 'required|string',
            'customer_data' => 'required|array',
            'customer_data.name' => 'required|string',
            'customer_data.email' => 'required|email',
            'customer_data.phone' => 'required|string',
            'customer_data.address' => 'required|string',
            'customer_data.city' => 'required|string',
            'customer_data.state' => 'required|string',
            'customer_data.postal_code' => 'required|string',
            'customer_data.country' => 'required|string|size:2'
        ]);

        $user = Auth::user();
        $template = Template::findOrFail($request->template_id);
        $fullDomain = $request->domain_name . '.' . $request->domain_extension;

        // Check if domain is still reserved by this user
        $domain = Domain::where('name', $fullDomain)
            ->where('user_id', $user->id)
            ->where('status', 'reserved')
            ->where('reserved_until', '>', now())
            ->first();

        if (!$domain) {
            return response()->json([
                'success' => false,
                'message' => 'Domain reservation expired or not found'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $user->id,
                'template_id' => $template->id,
                'domain_name' => $request->domain_name,
                'domain_extension' => $request->domain_extension,
                'template_price' => $template->price ?? 0,
                'domain_price' => $domain->price,
                'total_price' => ($template->price ?? 0) + $domain->price,
                'status' => 'pending',
                'customer_data' => $request->customer_data
            ]);

            // Update domain status
            $domain->update(['status' => 'reserved']); // Keep reserved until payment

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $order->load(['template', 'user']),
                'message' => 'Order created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single order
     */
    public function show(Order $order): JsonResponse
    {
        $user = Auth::user();

        // Check if order belongs to user
        if ($order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load(['template', 'payments'])
        ]);
    }

    /**
     * Get user orders
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with(['template', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
