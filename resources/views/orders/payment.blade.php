@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Payment Information -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6">Payment Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                        <div class="space-y-2">
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Domain:</strong> {{ $order->domain_name }}.{{ $order->domain_extension }}</p>
                            <p><strong>Template:</strong> {{ $order->template->name ?? 'N/A' }}</p>
                            <p><strong>Total Amount:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            <p><strong>Order Status:</strong>
                                <span id="order-status" class="px-2 py-1 rounded text-sm
                                    {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
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
                            <p><strong>Payment Status:</strong>
                                <span id="payment-status" class="px-2 py-1 rounded text-sm
                                    {{ in_array($payment->status, ['settlement', 'capture']) ? 'bg-green-100 text-green-800' :
                                       (in_array($payment->status, ['deny', 'cancel', 'expire', 'failure']) ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </p>
                            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Specific Content -->
                @if($payment->payment_type === 'snap')
                <!-- Snap Payment -->
                <div class="mb-6 text-center">
                    <h3 class="text-lg font-semibold mb-4">Complete Your Payment</h3>
                    @if(session('redirect_url'))
                    <div class="space-y-4">
                        <p class="text-gray-600 mb-4">Click the button below to complete your payment</p>
                        <button id="pay-button" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
                            Pay Now - Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </button>
                        <div class="mt-4">
                            <a href="{{ session('redirect_url') }}" target="_blank" class="text-blue-600 hover:underline">
                                Open Payment Page in New Tab
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                @elseif($payment->payment_type === 'qris' && isset($payment->payment_data['actions']))
                <!-- QRIS Payment -->
                <div class="mb-6 text-center">
                    <h3 class="text-lg font-semibold mb-4">Scan QR Code to Pay</h3>
                    <div class="inline-block bg-gray-50 p-4 rounded-lg">
                        @foreach($payment->payment_data['actions'] as $action)
                        @if($action['name'] === 'generate-qr-code')
                        <img src="{{ $action['url'] }}" alt="QR Code" class="mx-auto max-w-xs mb-4">
                        <p class="text-sm text-gray-600">Scan this QR code with any e-wallet app</p>
                        <p class="text-xs text-gray-500 mt-2">Payment will expire in 60 minutes</p>
                        @endif
                        @endforeach
                    </div>
                </div>

                @elseif(in_array($payment->payment_type, ['bank_transfer']) && isset($payment->payment_data['va_numbers']))
                <!-- Bank Transfer / Virtual Account -->
                <div class="mb-6 bg-blue-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Bank Transfer Instructions</h3>
                    <p class="text-sm text-gray-700 mb-4">Please transfer the exact amount to the virtual account number below:</p>

                    @foreach($payment->payment_data['va_numbers'] as $va)
                    <div class="bg-white p-4 rounded border mb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-lg">{{ strtoupper($va['bank']) }}</p>
                                <p class="text-2xl font-mono font-bold text-blue-600">{{ $va['va_number'] }}</p>
                                <p class="text-sm text-gray-600">Virtual Account Number</p>
                            </div>
                            <button onclick="copyToClipboard('{{ $va['va_number'] }}')"
                                class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                                Copy
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-4 text-sm text-gray-600">
                        <p><strong>Amount:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <p><strong>Payment Method:</strong> ATM, Internet Banking, or Mobile Banking</p>
                        <p class="text-red-600 mt-2"><strong>Important:</strong> Payment must be made within 24 hours</p>
                    </div>
                </div>

                @elseif($payment->payment_type === 'gopay')
                <!-- GoPay -->
                <div class="mb-6 text-center">
                    <h3 class="text-lg font-semibold mb-4">GoPay Payment</h3>
                    @if(isset($payment->payment_data['actions']))
                    @foreach($payment->payment_data['actions'] as $action)
                    @if($action['name'] === 'generate-qr-code')
                    <div class="inline-block bg-gray-50 p-4 rounded-lg">
                        <img src="{{ $action['url'] }}" alt="GoPay QR Code" class="mx-auto max-w-xs mb-4">
                        <p class="text-sm text-gray-600">Scan with GoPay app</p>
                    </div>
                    @elseif($action['name'] === 'deeplink-redirect')
                    <div class="mt-4">
                        <a href="{{ $action['url'] }}" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                            Open GoPay App
                        </a>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>

                @elseif($payment->payment_type === 'cstore')
                <!-- Convenience Store -->
                <div class="mb-6 bg-orange-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">
                        {{ ucfirst($payment->payment_data['store'] ?? 'Convenience Store') }} Payment
                    </h3>

                    @if(isset($payment->payment_data['payment_code']))
                    <div class="bg-white p-4 rounded border">
                        <p class="font-semibold">Payment Code:</p>
                        <p class="text-2xl font-mono font-bold text-orange-600">{{ $payment->payment_data['payment_code'] }}</p>
                        <button onclick="copyToClipboard('{{ $payment->payment_data['payment_code'] }}')"
                            class="mt-2 px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">
                            Copy Code
                        </button>
                    </div>
                    @endif

                    <div class="mt-4 text-sm text-gray-600">
                        <p>1. Go to the nearest {{ ucfirst($payment->payment_data['store'] ?? 'convenience store') }}</p>
                        <p>2. Show the payment code to the cashier</p>
                        <p>3. Pay the exact amount: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <p class="text-red-600 mt-2"><strong>Payment expires in 3 days</strong></p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mt-6">
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition duration-200">
                        Back to Home
                    </a>
                    <button onclick="checkPaymentStatus()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200">
                        <span id="check-status-text">Check Payment Status</span>
                    </button>
                    @if(in_array($payment->status, ['pending']))
                    <button onclick="cancelPayment()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-200">
                        Cancel Payment
                    </button>
                    @endif

                    @if(config('services.midtrans.sandbox') && in_array($payment->status, ['pending']))
                    <button onclick="manualPayment()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-200">
                        <span id="manual-pay-text">✓ Bayar (Simulasi)</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar with Sandbox Instructions -->
        <div class="lg:col-span-1">
            @if(config('services.midtrans.sandbox'))
            <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-4">🧪 Sandbox Mode</h3>
                <p class="text-sm text-yellow-700 mb-4">This is a test environment. No real money will be charged.</p>

                @if(!empty($sandboxInstructions))
                <div class="space-y-3">
                    @if(isset($sandboxInstructions['title']))
                    <h4 class="font-semibold text-yellow-800">{{ $sandboxInstructions['title'] }}</h4>
                    @endif

                    @if(isset($sandboxInstructions['cards']))
                    <div class="text-xs space-y-1">
                        <p><strong>Success Card:</strong> {{ $sandboxInstructions['cards']['visa_success'] }}</p>
                        <p><strong>Failure Card:</strong> {{ $sandboxInstructions['cards']['visa_failure'] }}</p>
                        <p><strong>CVV:</strong> {{ $sandboxInstructions['cvv'] }}</p>
                        <p><strong>Expiry:</strong> {{ $sandboxInstructions['expiry'] }}</p>
                        <p><strong>OTP:</strong> {{ $sandboxInstructions['otp'] }}</p>
                    </div>
                    @endif

                    @if(isset($sandboxInstructions['phone']))
                    <p class="text-xs"><strong>Test Phone:</strong> {{ $sandboxInstructions['phone'] }}</p>
                    <p class="text-xs"><strong>Test PIN:</strong> {{ $sandboxInstructions['pin'] }}</p>
                    @endif

                    @if(isset($sandboxInstructions['note']))
                    <p class="text-xs text-yellow-600">{{ $sandboxInstructions['note'] }}</p>
                    @endif
                </div>
                @endif
            </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Template ({{ $order->template->name ?? 'N/A' }})</span>
                        <span>Rp {{ number_format($order->template_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Domain ({{ $order->domain_name }}.{{ $order->domain_extension }})</span>
                        <span>Rp {{ number_format($order->domain_price, 0, ',', '.') }}</span>
                    </div>
                    @if($order->total_price < ($order->template_price + $order->domain_price))
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>-Rp {{ number_format(($order->template_price + $order->domain_price) - $order->total_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <hr class="my-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total</span>
                            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>Name:</strong> {{ $order->customer_data['name'] }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_data['email'] }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_data['phone'] }}</p>
                    @if(isset($order->customer_data['address']))
                    <p><strong>Address:</strong> {{ $order->customer_data['address'] }}</p>
                    @endif
                    @if(isset($order->customer_data['city']))
                    <p><strong>City:</strong> {{ $order->customer_data['city'] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Snap Payment Script -->
@if($payment->payment_type === 'snap' && session('redirect_url'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
@endif

<script>
    // Pass PHP data to JavaScript
    const paymentData = {
        type: '{{ $payment->payment_type }}',
        status: '{{ $payment->status }}',
        token: '{{ $payment->payment_data["token"] ?? "" }}',
        checkStatusUrl: '{{ route("api.orders.payment-status", ["order" => $order->id]) }}',
        homeUrl: '{{ route("home") }}',
        csrfToken: '{{ csrf_token() }}',
        isPending: {{ in_array($payment->status, ['pending']) ? 'true' : 'false' }}
    };

    // Snap Payment Handler
    if (paymentData.type === 'snap' && paymentData.token) {
        document.getElementById('pay-button').onclick = function() {
            snap.pay(paymentData.token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    checkPaymentStatus();
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    checkPaymentStatus();
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    checkPaymentStatus();
                }
            });
        };
    }

    // Check payment status
    function checkPaymentStatus() {
        const checkButton = document.getElementById('check-status-text');
        checkButton.textContent = 'Checking...';

        fetch(paymentData.checkStatusUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': paymentData.csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update status displays
                    const paymentStatus = document.getElementById('payment-status');
                    const orderStatus = document.getElementById('order-status');

                    // Update payment status
                    paymentStatus.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    paymentStatus.className = 'px-2 py-1 rounded text-sm ' + getStatusClass(data.status);

                    // Update order status
                    orderStatus.textContent = data.order_status.charAt(0).toUpperCase() + data.order_status.slice(1);
                    orderStatus.className = 'px-2 py-1 rounded text-sm ' + getStatusClass(data.order_status);

                    // Show success message if paid
                    if (data.status === 'settlement' || data.status === 'capture' || data.order_status === 'paid') {
                        showMessage('Payment successful! Your order has been confirmed.', 'success');
                        // Stop auto-refresh when payment is complete
                        if (window.autoRefreshInterval) {
                            clearInterval(window.autoRefreshInterval);
                        }
                    }
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error checking payment status', 'error');
            })
            .finally(() => {
                checkButton.textContent = 'Check Payment Status';
            });
    }

    // Cancel payment
    function cancelPayment() {
        if (confirm('Are you sure you want to cancel this payment?')) {
            // Implementation for cancelling payment
            window.location.href = paymentData.homeUrl;
        }
    }

    // Copy to clipboard
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            showMessage('Copied to clipboard!', 'success');
        }, function(err) {
            console.error('Could not copy text: ', err);
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showMessage('Copied to clipboard!', 'success');
            } catch (err) {
                console.error('Fallback copy failed: ', err);
            }
            document.body.removeChild(textArea);
        });
    }

    // Get status CSS class
    function getStatusClass(status) {
        if (['settlement', 'capture', 'paid'].includes(status)) {
            return 'bg-green-100 text-green-800';
        } else if (['deny', 'cancel', 'expire', 'failure', 'cancelled'].includes(status)) {
            return 'bg-red-100 text-red-800';
        } else {
            return 'bg-yellow-100 text-yellow-800';
        }
    }

    // Show message
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
            'bg-blue-100 border-blue-400 text-blue-700';

        const messageDiv = document.createElement('div');
        messageDiv.className = `border px-4 py-3 rounded mb-4 relative ${alertClass}`;
        messageDiv.innerHTML = `
        <span class="block sm:inline">${message}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
            <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </span>
    `;

        const container = document.querySelector('.max-w-6xl');
        container.insertBefore(messageDiv, container.firstChild);

        // Auto-hide success messages after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 5000);
        }
    }

    // Auto-refresh for pending payments
    if (paymentData.isPending) {
        let autoRefreshInterval = setInterval(function() {
            const paymentStatusElement = document.getElementById('payment-status');
            const currentStatus = paymentStatusElement.textContent.toLowerCase();

            if (['pending'].includes(currentStatus)) {
                checkPaymentStatus();
            } else {
                clearInterval(autoRefreshInterval);
            }
        }, 10000); // Check every 10 seconds

        // Store interval globally so it can be accessed from other functions
        window.autoRefreshInterval = autoRefreshInterval;

        // Clear interval when page is about to unload
        window.addEventListener('beforeunload', function() {
            clearInterval(autoRefreshInterval);
        });
    }

    function manualPayment() {
        if (!confirm('Apakah Anda yakin ingin menandai pembayaran ini sebagai berhasil? Email invoice akan dikirim ke customer.')) {
            return;
        }

        const manualButton = document.getElementById('manual-pay-text');
        const originalText = manualButton.textContent;
        manualButton.textContent = 'Processing...';

        fetch(`/orders/{{ $order->id }}/manual-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': paymentData.csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update status displays
                    const paymentStatus = document.getElementById('payment-status');
                    const orderStatus = document.getElementById('order-status');

                    // Update payment status
                    paymentStatus.textContent = 'Settlement';
                    paymentStatus.className = 'px-2 py-1 rounded text-sm bg-green-100 text-green-800';

                    // Update order status
                    orderStatus.textContent = 'Paid';
                    orderStatus.className = 'px-2 py-1 rounded text-sm bg-green-100 text-green-800';

                    // Show success message
                    showMessage(data.message, 'success');

                    // Hide the manual payment button
                    document.querySelector('button[onclick="manualPayment()"]').style.display = 'none';

                    // Stop auto-refresh
                    if (window.autoRefreshInterval) {
                        clearInterval(window.autoRefreshInterval);
                    }
                } else {
                    showMessage(data.message, 'error');
                    manualButton.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error processing manual payment', 'error');
                manualButton.textContent = originalText;
            });
    }
</script>
@endsection
