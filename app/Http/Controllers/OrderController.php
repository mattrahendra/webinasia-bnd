<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Template;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:user,admin');
    }

    public function index()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with(['template', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            return redirect()->route('orders.index')->with('error', 'Order not found');
        }

        $order->load(['template', 'payments']);
        return view('orders.show', compact('order'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'domain_name' => 'required|string',
            'domain_extension' => 'required|string',
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

        $domain = Domain::where('name', $fullDomain)
            ->where('user_id', $user->id)
            ->where('status', 'reserved')
            ->where('reserved_until', '>', now())
            ->first();

        if (!$domain) {
            return redirect()->back()->with('error', 'Domain reservation expired or not found');
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

            $domain->update(['status' => 'reserved']);

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }
}
