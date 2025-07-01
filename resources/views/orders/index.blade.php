@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">My Orders</h1>
        @if ($orders->isEmpty())
            <p>No orders found.</p>
        @else
            <div class="grid gap-4">
                @foreach ($orders as $order)
                    @include('components.card', [
                        'title' => 'Order #' . $order->order_number,
                        'description' => 'Template: ' . ($order->template->name ?? 'N/A') . ' | Domain: ' . $order->domain_name . '.' . $order->domain_extension . ' | Status: ' . $order->status,
                        'price' => '$' . number_format($order->total_price, 2),
                        'link' => route('orders.show', $order)
                    ])
                @endforeach
            </div>
            {{ $orders->links() }}
        @endif
    </div>
@endsection
