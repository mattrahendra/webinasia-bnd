@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Order #{{ $order->order_number }}</h1>
        <div class="bg-white shadow rounded-lg p-6">
            <p><strong>Template:</strong> {{ $order->template->name ?? 'N/A' }}</p>
            <p><strong>Domain:</strong> {{ $order->domain_name . '.' . $order->domain_extension }}</p>
            <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p><strong>Customer:</strong> {{ $order->customer_data['name'] }}</p>
            <p><strong>Email:</strong> {{ $order->customer_data['email'] }}</p>
        </div>

        @if ($order->status == 'pending' && $order->payments->isEmpty())
            <form action="{{ route('payments.process', $order) }}" method="POST" class="mt-6">
                @csrf
                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="p-2 border rounded w-full" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="e_wallet">E-Wallet</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                @include('components.button', ['label' => 'Proceed to Payment', 'icon' => 'fas fa-credit-card'])
            </form>
        @endif
    </div>
@endsection
