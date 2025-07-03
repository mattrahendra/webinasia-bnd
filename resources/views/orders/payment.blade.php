@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">Payment Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                <div class="space-y-2">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Domain:</strong> {{ $order->domain_name }}.{{ $order->domain_extension }}</p>
                    <p><strong>Template:</strong> {{ $order->template->name ?? 'N/A' }}</p>
                    <p><strong>Total Amount:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-sm {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                <div class="space-y-2">
                    <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    <p><strong>Payment Type:</strong> {{ ucfirst($payment->payment_type) }}</p>
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 rounded text-sm {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </p>
                    <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                </div>
            </div>
        </div>

        @if($payment->payment_type === 'qris' && isset($payment->payment_data['actions']))
            <div class="mt-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Scan QR Code to Pay</h3>
                <div class="inline-block">
                    @foreach($payment->payment_data['actions'] as $action)
                        @if($action['name'] === 'generate-qr-code')
                            <img src="{{ $action['url'] }}" alt="QR Code" class="mx-auto max-w-xs">
                            <p class="mt-2 text-sm text-gray-600">Scan this QR code with your e-wallet app</p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if($payment->payment_type === 'bank_transfer')
            <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Bank Transfer Instructions</h3>
                <p class="text-sm text-gray-700">Please transfer the amount to the virtual account number provided by Midtrans.</p>
                @if(isset($payment->payment_data['va_numbers']))
                    @foreach($payment->payment_data['va_numbers'] as $va)
                        <p class="mt-2"><strong>{{ strtoupper($va['bank']) }} Virtual Account:</strong> {{ $va['va_number'] }}</p>
                    @endforeach
                @endif
            </div>
        @endif

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Back to Orders
            </a>
            <button onclick="checkPaymentStatus()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Check Payment Status
            </button>
        </div>
    </div>
</div>

<script>
function checkPaymentStatus() {
    // You can implement AJAX call to check payment status
    window.location.reload();
}

// Auto-refresh page every 30 seconds to check payment status
setInterval(function() {
    if (document.getElementById('payment-status').textContent.trim() === 'Pending') {
        checkPaymentStatus();
    }
}, 30000);
</script>
@endsection
