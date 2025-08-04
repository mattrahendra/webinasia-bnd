@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-navy-50 to-custom-blue-50">


    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Payment Information -->
            <div class="lg:col-span-2">
                <div class="bg-bone-50 rounded-3xl shadow-xl border-2 border-navy-100 overflow-hidden">
                    <!-- Payment Status Header -->
                    <div class="bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-3xl font-black mb-2">Payment Details</h2>
                                <p class="text-custom-blue-200">Transaction ID: {{ $payment->transaction_id }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-bone-50 text-lg font-semibold mb-2">Payment Status</p>
                                <span id="payment-status" class="inline-flex items-center px-4 py-3 rounded-2xl text-sm font-bold border-2
                                    {{ in_array($payment->status, ['settlement', 'capture']) ? 'bg-green-100 text-green-800 border-green-300' :
                                       (in_array($payment->status, ['deny', 'cancel', 'expire', 'failure']) ? 'bg-red-100 text-red-800 border-red-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300') }}">
                                    <i class="fas fa-{{ in_array($payment->status, ['settlement', 'capture']) ? 'check-circle' : (in_array($payment->status, ['deny', 'cancel', 'expire', 'failure']) ? 'times-circle' : 'clock') }} mr-2"></i>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-10">
                        <!-- Order Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                            <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl p-6 border-2 border-custom-blue-200">
                                <h3 class="text-xl font-black text-navy-900 mb-6 flex items-center">
                                    <i class="fas fa-shopping-bag mr-3 text-custom-blue-600"></i>
                                    Order Information
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Order Number:</span>
                                        <span class="font-bold text-navy-900">{{ $order->order_number }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Domain:</span>
                                        <span class="font-bold text-custom-blue-600">{{ $order->domain_name }}.{{ $order->domain_extension }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Template:</span>
                                        <span class="font-bold text-navy-900">{{ $order->template->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 border-t-2 border-custom-blue-200">
                                        <span class="text-navy-600 font-medium">Total Amount:</span>
                                        <span class="font-black text-2xl text-custom-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Order Status:</span>
                                        <span id="order-status" class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-bold
                                            {{ $order->status === 'paid' ? 'bg-green-100 text-green-800' :
                                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            <i class="fas fa-{{ $order->status === 'paid' ? 'check-circle' : ($order->status === 'cancelled' ? 'times-circle' : 'clock') }} mr-2"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl p-6 border-2 border-custom-yellow-200">
                                <h3 class="text-xl font-black text-navy-900 mb-6 flex items-center">
                                    <i class="fas fa-wallet mr-3 text-custom-yellow-600"></i>
                                    Payment Information
                                </h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Payment Method:</span>
                                        <span class="font-bold text-navy-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Payment Type:</span>
                                        <span class="font-bold text-navy-900">{{ ucfirst($payment->payment_type) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-navy-600 font-medium">Transaction ID:</span>
                                        <span class="font-mono text-sm text-navy-700 bg-navy-100 px-3 py-1 rounded-lg">{{ $payment->transaction_id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Specific Content -->
                        @if($payment->payment_type === 'snap')
                        <!-- Snap Payment -->
                        <div class="text-center mb-10">
                            <div class="bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-3xl p-10 text-bone-50">
                                <div class="text-6xl mb-6">
                                    <i class="fas fa-credit-card text-custom-yellow-400"></i>
                                </div>
                                <h3 class="text-2xl font-black mb-6">Complete Your Payment</h3>
                                @if(session('redirect_url'))
                                <div class="space-y-6">
                                    <p class="text-custom-blue-200 text-lg mb-6">Click the button below to complete your payment securely</p>
                                    <button id="pay-button" class="inline-flex items-center px-10 py-6 bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 font-black text-xl rounded-2xl hover:from-custom-yellow-500 hover:to-custom-yellow-600 transition-all duration-300 hover:scale-110 shadow-2xl hover:shadow-yellow-glow">
                                        <i class="fas fa-lock mr-4"></i>Pay Now - Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </button>
                                    <div class="mt-6">
                                        <a href="{{ session('redirect_url') }}" target="_blank" class="inline-flex items-center text-custom-blue-200 hover:text-bone-50 font-semibold transition-colors duration-300">
                                            <i class="fas fa-external-link-alt mr-2"></i>Open Payment Page in New Tab
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @elseif($payment->payment_type === 'qris' && isset($payment->payment_data['actions']))
                        <!-- QRIS Payment -->
                        <div class="text-center mb-10">
                            <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-3xl p-10 border-2 border-custom-blue-200">
                                <div class="text-6xl mb-6">
                                    <i class="fas fa-qrcode text-custom-blue-600"></i>
                                </div>
                                <h3 class="text-2xl font-black text-navy-900 mb-6">Scan QR Code to Pay</h3>
                                <div class="inline-block bg-bone-50 p-6 rounded-2xl border-2 border-navy-200 shadow-xl">
                                    @foreach($payment->payment_data['actions'] as $action)
                                    @if($action['name'] === 'generate-qr-code')
                                    <img src="{{ $action['url'] }}" alt="QR Code" class="mx-auto max-w-xs mb-4 rounded-lg">
                                    <p class="text-navy-600 font-semibold mb-2">Scan this QR code with any e-wallet app</p>
                                    <p class="text-sm text-navy-500 bg-yellow-100 px-4 py-2 rounded-lg">Payment will expire in 60 minutes</p>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @elseif(in_array($payment->payment_type, ['bank_transfer']) && isset($payment->payment_data['va_numbers']))
                        <!-- Bank Transfer / Virtual Account -->
                        <div class="mb-10">
                            <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-3xl p-10 border-2 border-custom-blue-200">
                                <div class="text-center mb-8">
                                    <div class="text-6xl mb-4">
                                        <i class="fas fa-university text-custom-blue-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-navy-900 mb-4">Bank Transfer Instructions</h3>
                                    <p class="text-navy-600 text-lg">Please transfer the exact amount to the virtual account number below</p>
                                </div>

                                @foreach($payment->payment_data['va_numbers'] as $va)
                                <div class="bg-bone-50 rounded-2xl p-8 border-2 border-navy-200 mb-6 shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="text-center flex-1">
                                            <p class="text-2xl font-black text-navy-900 mb-2">{{ strtoupper($va['bank']) }}</p>
                                            <p class="text-4xl font-mono font-black text-custom-blue-600 mb-2">{{ $va['va_number'] }}</p>
                                            <p class="text-navy-600 font-semibold">Virtual Account Number</p>
                                        </div>
                                        <button onclick="copyToClipboard('{{ $va['va_number'] }}')"
                                            class="ml-6 px-6 py-4 bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 font-bold rounded-2xl hover:from-custom-yellow-500 hover:to-custom-yellow-600 transition-all duration-300 hover:scale-110 shadow-lg">
                                            <i class="fas fa-copy mr-2"></i>Copy
                                        </button>
                                    </div>
                                </div>
                                @endforeach

                                <div class="bg-yellow-100 rounded-2xl p-6 border-2 border-yellow-300">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-navy-700">
                                        <div>
                                            <p class="font-bold text-lg mb-2"><i class="fas fa-money-bill mr-2 text-yellow-600"></i>Amount:</p>
                                            <p class="text-2xl font-black text-custom-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="font-bold text-lg mb-2"><i class="fas fa-credit-card mr-2 text-yellow-600"></i>Payment Method:</p>
                                            <p class="font-semibold">ATM, Internet Banking, or Mobile Banking</p>
                                        </div>
                                    </div>
                                    <div class="mt-6 p-4 bg-red-100 rounded-xl border-2 border-red-300">
                                        <p class="text-red-700 font-bold text-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>Important: Payment must be made within 24 hours
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @elseif($payment->payment_type === 'gopay')
                        <!-- GoPay -->
                        <div class="text-center mb-10">
                            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-3xl p-10 border-2 border-green-300">
                                <div class="text-6xl mb-6">
                                    <i class="fab fa-google-wallet text-green-600"></i>
                                </div>
                                <h3 class="text-2xl font-black text-navy-900 mb-6">GoPay Payment</h3>
                                @if(isset($payment->payment_data['actions']))
                                @foreach($payment->payment_data['actions'] as $action)
                                @if($action['name'] === 'generate-qr-code')
                                <div class="inline-block bg-bone-50 p-6 rounded-2xl border-2 border-navy-200 shadow-xl mb-6">
                                    <img src="{{ $action['url'] }}" alt="GoPay QR Code" class="mx-auto max-w-xs mb-4 rounded-lg">
                                    <p class="text-navy-600 font-semibold">Scan with GoPay app</p>
                                </div>
                                @elseif($action['name'] === 'deeplink-redirect')
                                <div class="mt-6">
                                    <a href="{{ $action['url'] }}" class="inline-flex items-center px-10 py-6 bg-gradient-to-r from-green-600 to-green-700 text-bone-50 font-black text-xl rounded-2xl hover:from-green-700 hover:to-green-800 transition-all duration-300 hover:scale-110 shadow-2xl">
                                        <i class="fab fa-google-wallet mr-4"></i>Open GoPay App
                                    </a>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>
                        </div>

                        @elseif($payment->payment_type === 'cstore')
                        <!-- Convenience Store -->
                        <div class="mb-10">
                            <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-3xl p-10 border-2 border-orange-300">
                                <div class="text-center mb-8">
                                    <div class="text-6xl mb-4">
                                        <i class="fas fa-store text-orange-600"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-navy-900 mb-4">
                                        {{ ucfirst($payment->payment_data['store'] ?? 'Convenience Store') }} Payment
                                    </h3>
                                </div>

                                @if(isset($payment->payment_data['payment_code']))
                                <div class="bg-bone-50 rounded-2xl p-8 border-2 border-navy-200 mb-6 shadow-lg text-center">
                                    <p class="text-xl font-bold text-navy-900 mb-4">Payment Code:</p>
                                    <p class="text-5xl font-mono font-black text-orange-600 mb-6">{{ $payment->payment_data['payment_code'] }}</p>
                                    <button onclick="copyToClipboard('{{ $payment->payment_data['payment_code'] }}')"
                                        class="px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-bone-50 font-bold rounded-2xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300 hover:scale-110 shadow-lg">
                                        <i class="fas fa-copy mr-3"></i>Copy Code
                                    </button>
                                </div>
                                @endif

                                <div class="bg-orange-100 rounded-2xl p-6 border-2 border-orange-300">
                                    <div class="space-y-4 text-navy-700">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 bg-orange-500 text-bone-50 rounded-full flex items-center justify-center mr-4 font-bold">1</span>
                                            <span class="font-semibold">Go to the nearest {{ ucfirst($payment->payment_data['store'] ?? 'convenience store') }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 bg-orange-500 text-bone-50 rounded-full flex items-center justify-center mr-4 font-bold">2</span>
                                            <span class="font-semibold">Show the payment code to the cashier</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 bg-orange-500 text-bone-50 rounded-full flex items-center justify-center mr-4 font-bold">3</span>
                                            <span class="font-semibold">Pay the exact amount: <span class="text-orange-700 font-black">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></span>
                                        </div>
                                    </div>
                                    <div class="mt-6 p-4 bg-red-100 rounded-xl border-2 border-red-300">
                                        <p class="text-red-700 font-bold text-center">
                                            <i class="fas fa-clock mr-2"></i>Payment expires in 3 days
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4 justify-center">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-navy-200 to-navy-300 text-navy-700 font-bold rounded-2xl hover:from-navy-300 hover:to-navy-400 transition-all duration-300 hover:scale-110 shadow-lg">
                                <i class="fas fa-home mr-3"></i>Back to Home
                            </a>

                            @if(in_array($payment->status, ['pending']))
                            <button onclick="cancelPayment()" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-red-500 to-red-600 text-bone-50 font-bold rounded-2xl hover:from-red-600 hover:to-red-700 transition-all duration-300 hover:scale-110 shadow-lg">
                                <i class="fas fa-times mr-3"></i>Cancel Payment
                            </button>
                            @endif

                            @if(config('services.midtrans.sandbox') && in_array($payment->status, ['pending']))
                            <button onclick="manualPayment()" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-bone-50 font-bold rounded-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300 hover:scale-110 shadow-lg">
                                <span id="manual-pay-text"><i class="fas fa-check mr-3"></i>Bayar (Simulasi)</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                @if(config('services.midtrans.sandbox'))
                <!-- Sandbox Instructions -->
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-3xl p-8 shadow-xl">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-flask text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-yellow-800">🧪 Sandbox Mode</h3>
                        <p class="text-yellow-700 font-medium mt-2">This is a test environment. No real money will be charged.</p>
                    </div>

                    @if(!empty($sandboxInstructions))
                    <div class="space-y-4">
                        @if(isset($sandboxInstructions['title']))
                        <h4 class="font-bold text-yellow-800 text-lg">{{ $sandboxInstructions['title'] }}</h4>
                        @endif

                        @if(isset($sandboxInstructions['cards']))
                        <div class="bg-yellow-100 rounded-2xl p-4 text-sm space-y-2">
                            <p><strong>Success Card:</strong> <span class="font-mono">{{ $sandboxInstructions['cards']['visa_success'] }}</span></p>
                            <p><strong>Failure Card:</strong> <span class="font-mono">{{ $sandboxInstructions['cards']['visa_failure'] }}</span></p>
                            <p><strong>CVV:</strong> <span class="font-mono">{{ $sandboxInstructions['cvv'] }}</span></p>
                            <p><strong>Expiry:</strong> <span class="font-mono">{{ $sandboxInstructions['expiry'] }}</span></p>
                            <p><strong>OTP:</strong> <span class="font-mono">{{ $sandboxInstructions['otp'] }}</span></p>
                        </div>
                        @endif

                        @if(isset($sandboxInstructions['phone']))
                        <div class="bg-yellow-100 rounded-2xl p-4 text-sm">
                            <p><strong>Test Phone:</strong> <span class="font-mono">{{ $sandboxInstructions['phone'] }}</span></p>
                            <p><strong>Test PIN:</strong> <span class="font-mono">{{ $sandboxInstructions['pin'] }}</span></p>
                        </div>
                        @endif

                        @if(isset($sandboxInstructions['note']))
                        <p class="text-sm text-yellow-700 bg-yellow-100 rounded-xl p-3">{{ $sandboxInstructions['note'] }}</p>
                        @endif
                    </div>
                    @endif
                </div>
                @endif

                <!-- Order Summary -->
                <div class="bg-bone-50 rounded-3xl shadow-xl border-2 border-navy-100 p-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-4">
                            <i class="fas fa-receipt text-bone-50 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-navy-900">Order Summary</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl border border-custom-blue-200">
                            <span class="text-navy-600 font-medium">Template ({{ $order->template->name ?? 'N/A' }})</span>
                            <span class="font-bold text-navy-900">Rp {{ number_format($order->template_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl border border-custom-blue-200">
                            <span class="text-navy-600 font-medium">Domain ({{ $order->domain_name }}.{{ $order->domain_extension }})</span>
                            <span class="font-bold text-navy-900">Rp {{ number_format($order->domain_price, 0, ',', '.') }}</span>
                        </div>
                        @if($order->total_price < ($order->template_price + $order->domain_price))
                        <div class="flex justify-between items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-2xl border border-green-200">
                            <span class="text-green-600 font-medium">Discount</span>
                            <span class="font-bold text-green-700">-Rp {{ number_format(($order->template_price + $order->domain_price) - $order->total_price, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="border-t-2 border-navy-200 pt-4">
                            <div class="flex justify-between items-center p-4 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-2xl text-bone-50">
                                <span class="font-bold text-xl">Total</span>
                                <span class="font-black text-2xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-bone-50 rounded-3xl shadow-xl border-2 border-navy-100 p-8">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 rounded-full mb-4">
                            <i class="fas fa-user text-bone-50 text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-navy-900">Customer Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl border border-custom-yellow-200">
                            <p class="text-navy-600 font-medium mb-1">Name</p>
                            <p class="font-bold text-navy-900">{{ $order->customer_data['name'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl border border-custom-yellow-200">
                            <p class="text-navy-600 font-medium mb-1">Email</p>
                            <p class="font-bold text-navy-900">{{ $order->customer_data['email'] }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl border border-custom-yellow-200">
                            <p class="text-navy-600 font-medium mb-1">Phone</p>
                            <p class="font-bold text-navy-900">{{ $order->customer_data['phone'] }}</p>
                        </div>
                        @if(isset($order->customer_data['address']))
                        <div class="p-4 bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl border border-custom-yellow-200">
                            <p class="text-navy-600 font-medium mb-1">Address</p>
                            <p class="font-bold text-navy-900">{{ $order->customer_data['address'] }}</p>
                        </div>
                        @endif
                        @if(isset($order->customer_data['city']))
                        <div class="p-4 bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl border border-custom-yellow-200">
                            <p class="text-navy-600 font-medium mb-1">City</p>
                            <p class="font-bold text-navy-900">{{ $order->customer_data['city'] }}</p>
                        </div>
                        @endif
                    </div>
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
                    showMessage('Payment successful! Your order has been confirmed.', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    showMessage('Payment is being processed. Please wait...', 'info');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    showMessage('Payment failed. Please try again.', 'error');
                }
            });
        };
    }

    // Cancel payment
    function cancelPayment() {
        if (confirm('Are you sure you want to cancel this payment?')) {
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

    // Show message
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
            type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
            type === 'info' ? 'bg-blue-100 border-blue-400 text-blue-700' :
            'bg-yellow-100 border-yellow-400 text-yellow-700';

        const iconClass = type === 'success' ? 'fas fa-check-circle' :
            type === 'error' ? 'fas fa-times-circle' :
            type === 'info' ? 'fas fa-info-circle' :
            'fas fa-exclamation-triangle';

        const messageDiv = document.createElement('div');
        messageDiv.className = `fixed top-6 right-6 max-w-md border-2 px-6 py-4 rounded-2xl shadow-2xl z-50 ${alertClass}`;
        messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="${iconClass} text-2xl mr-4"></i>
            <div class="flex-1">
                <span class="font-bold">${message}</span>
            </div>
            <button class="ml-4 text-xl hover:scale-110 transition-transform duration-200" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

        document.body.appendChild(messageDiv);

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }

    // Auto-refresh for pending payments (every 30 seconds)
    if (paymentData.isPending) {
        let autoRefreshInterval = setInterval(function() {
            const paymentStatusElement = document.getElementById('payment-status');
            const currentStatus = paymentStatusElement.textContent.toLowerCase().trim();

            if (['pending'].includes(currentStatus)) {
                // Just reload the page to get updated status
                window.location.reload();
            } else {
                clearInterval(autoRefreshInterval);
            }
        }, 30000); // Check every 30 seconds

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

        const manualButton = document.querySelector('#manual-pay-text');
        const originalText = manualButton.innerHTML;
        manualButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Processing...';

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
                    paymentStatus.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Settlement';
                    paymentStatus.className = 'inline-flex items-center px-4 py-3 rounded-2xl text-sm font-bold border-2 bg-green-100 text-green-800 border-green-300';

                    // Update order status
                    orderStatus.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Paid';
                    orderStatus.className = 'inline-flex items-center px-3 py-2 rounded-xl text-sm font-bold bg-green-100 text-green-800';

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
                    manualButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error processing manual payment', 'error');
                manualButton.innerHTML = originalText;
            });
    }
</script>

<!-- Custom Styles -->
<style>
    /* Smooth transitions for all elements */
    * {
        transition: all 0.3s ease;
    }

    /* Button hover effects */
    button:hover:not(:disabled), a:hover {
        transform: translateY(-2px);
    }

    /* Card hover animations */
    .lg\:col-span-1 > div {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .lg\:col-span-1 > div:hover {
        transform: translateY(-5px);
    }

    /* QR Code and payment method animations */
    img {
        transition: all 0.3s ease;
    }

    img:hover {
        transform: scale(1.05);
    }

    /* Status badge animations */
    #payment-status, #order-status {
        transition: all 0.5s ease;
    }

    /* Loading animation for buttons */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Custom shadow effects */
    .shadow-yellow-glow {
        box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .grid-cols-1.md\:grid-cols-2 {
            grid-template-columns: 1fr;
        }

        .text-4xl.md\:text-5xl {
            font-size: 2.5rem;
        }

        .px-10 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-6 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    }

    /* Animation for page load */
    .lg\:col-span-2, .lg\:col-span-1 {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
