@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-navy-50 to-custom-blue-50">
    <!-- Header Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50 py-20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=" 60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg fill="%23fbbf24" fill-opacity="0.1" %3E%3Ccircle cx="30" cy="30" r="2" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="mb-6">
                    <i class="fas fa-shopping-cart text-5xl text-custom-yellow-400 mb-4 animate-pulse"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-black mb-4 bg-gradient-to-r from-bone-50 via-custom-blue-200 to-custom-yellow-300 bg-clip-text text-transparent">
                    Complete Your Order
                </h1>
                <p class="text-xl text-custom-blue-200 font-light">
                    Follow these simple steps to secure your domain and template
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Progress Steps Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-bone-50 rounded-3xl shadow-xl p-8 border-2 border-navy-100 sticky top-8">
                    <h3 class="text-2xl font-black text-navy-900 mb-8 text-center">Order Progress</h3>
                    <div class="steps space-y-6">
                        <div class="step active flex items-center p-4 rounded-2xl bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 shadow-lg">
                            <div class="w-10 h-10 rounded-full bg-bone-50 text-navy-900 font-black flex items-center justify-center mr-4 text-lg">1</div>
                            <span class="text-lg font-bold">Domain</span>
                        </div>
                        <div class="step flex items-center p-4 rounded-2xl bg-navy-100 text-navy-500">
                            <div class="w-10 h-10 rounded-full bg-navy-200 text-navy-500 font-black flex items-center justify-center mr-4 text-lg">2</div>
                            <span class="text-lg font-semibold">Template</span>
                        </div>
                        <div class="step flex items-center p-4 rounded-2xl bg-navy-100 text-navy-500">
                            <div class="w-10 h-10 rounded-full bg-navy-200 text-navy-500 font-black flex items-center justify-center mr-4 text-lg">3</div>
                            <span class="text-lg font-semibold">Data Diri</span>
                        </div>
                        <div class="step flex items-center p-4 rounded-2xl bg-navy-100 text-navy-500">
                            <div class="w-10 h-10 rounded-full bg-navy-200 text-navy-500 font-black flex items-center justify-center mr-4 text-lg">4</div>
                            <span class="text-lg font-semibold">Langganan & Add-ons</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-bone-50 rounded-3xl shadow-xl border-2 border-navy-100 overflow-hidden">
                    <form id="order-form" method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <!-- Step 1: Domain Selection -->
                        <div class="step-content p-10" id="step-1">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-6">
                                    <i class="fas fa-globe text-bone-50 text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-black text-navy-900 mb-4">Domain Selection</h2>
                                <p class="text-navy-600 text-lg">Your chosen domain details</p>
                            </div>

                            <div class="max-w-2xl mx-auto">
                                <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl p-8 border-2 border-custom-blue-200 text-center">
                                    <div class="text-6xl mb-6">
                                        <i class="fas fa-dot-circle text-custom-blue-500"></i>
                                    </div>
                                    <h3 class="text-3xl font-black text-navy-900 mb-4">{{ $domainData['full_domain'] }}</h3>
                                    <p class="text-2xl font-bold text-custom-blue-600">Rp {{ number_format($domainData['price'], 0, ',', '.') }} <span class="text-lg font-medium text-navy-600">/ tahun</span></p>
                                </div>
                            </div>

                            <input type="hidden" name="domain_name" value="{{ $domainData['domain_name'] }}">
                            <input type="hidden" name="extension" value="{{ $domainData['extension'] }}">
                            <input type="hidden" name="domain_price" value="{{ $domainData['price'] }}">
                        </div>

                        <!-- Step 2: Template Selection -->
                        <div class="step-content hidden p-10" id="step-2">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-6">
                                    <i class="fas fa-palette text-bone-50 text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-black text-navy-900 mb-4">Pilih Template</h2>
                                <p class="text-navy-600 text-lg">Choose a professional template for your website</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                                @foreach($templates as $template)
                                    <div class="group relative bg-gradient-to-br from-bone-50 to-custom-blue-50 rounded-2xl border-2 border-navy-100 hover:border-custom-blue-300 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl overflow-hidden">
                                        <label class="block cursor-pointer p-6">
                                            <div class="flex items-start space-x-4">
                                                <input type="radio" name="template_id" value="{{ $template->id }}" required class="mt-2 w-5 h-5 text-custom-blue-600 border-2 border-navy-300 focus:ring-custom-blue-500 focus:ring-2">
                                                <div class="flex-1">
                                                    <h3 class="text-xl font-bold text-navy-900 mb-2 group-hover:text-custom-blue-600 transition-colors duration-300">{{ $template->name }}</h3>
                                                    <p class="text-2xl font-black text-custom-blue-600 mb-4">Rp {{ number_format($template->price, 0, ',', '.') }}</p>
                                                    <a href="{{ route('templates.preview', $template) }}" target="_blank" class="inline-flex items-center text-custom-blue-600 hover:text-navy-700 font-semibold transition-colors duration-300">
                                                        <i class="fas fa-eye mr-2"></i>Preview Template
                                                    </a>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Personal Data -->
                        <div class="step-content hidden p-10" id="step-3">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-6">
                                    <i class="fas fa-user text-bone-50 text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-black text-navy-900 mb-4">Data Diri</h2>
                                <p class="text-navy-600 text-lg">Please provide your personal information</p>
                            </div>

                            <div class="max-w-3xl mx-auto space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-navy-700 font-semibold mb-3 text-lg">Nama Lengkap *</label>
                                        <input name="name" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg transition-all duration-300" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div>
                                        <label class="block text-navy-700 font-semibold mb-3 text-lg">Email *</label>
                                        <input name="email" type="email" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg transition-all duration-300" placeholder="contoh@email.com" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-navy-700 font-semibold mb-3 text-lg">Nomor HP *</label>
                                    <input name="phone" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg transition-all duration-300" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl p-8 border-2 border-custom-blue-200">
                                    <h3 class="text-xl font-bold text-navy-900 mb-6 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-3 text-custom-blue-600"></i>
                                        Alamat (Opsional)
                                    </h3>
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-navy-700 font-semibold mb-3">Alamat Lengkap</label>
                                            <textarea name="address" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 transition-all duration-300" placeholder="Jalan, nomor, RT/RW, kelurahan" rows="3"></textarea>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-navy-700 font-semibold mb-3">Kota</label>
                                                <input name="city" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 transition-all duration-300" placeholder="Nama kota">
                                            </div>
                                            <div>
                                                <label class="block text-navy-700 font-semibold mb-3">Kode Pos</label>
                                                <input name="postal_code" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 transition-all duration-300" placeholder="12345">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Subscription & Add-ons -->
                        <div class="step-content hidden p-10" id="step-4">
                            <div class="text-center mb-10">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-6">
                                    <i class="fas fa-credit-card text-bone-50 text-2xl"></i>
                                </div>
                                <h2 class="text-3xl font-black text-navy-900 mb-4">Langganan & Add-ons</h2>
                                <p class="text-navy-600 text-lg">Choose your subscription plan and payment method</p>
                            </div>

                            <div class="max-w-3xl mx-auto space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl p-6 border-2 border-custom-blue-200">
                                        <label class="block text-navy-700 font-bold mb-4 text-lg flex items-center">
                                            <i class="fas fa-calendar-alt mr-3 text-custom-blue-600"></i>
                                            Durasi Langganan
                                        </label>
                                        <select name="years" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg font-semibold transition-all duration-300" required>
                                            @for($i = 1; $i <= 5; $i++)
                                                <option value="{{ $i }}">{{ $i }} tahun</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-2xl p-6 border-2 border-custom-blue-200">
                                        <label class="block text-navy-700 font-bold mb-4 text-lg flex items-center">
                                            <i class="fas fa-wallet mr-3 text-custom-blue-600"></i>
                                            Metode Pembayaran
                                        </label>
                                        <select name="payment_method" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg font-semibold transition-all duration-300" required>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="e_wallet">E-Wallet (GoPay)</option>
                                            <option value="qris">QRIS</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-custom-yellow-50 to-custom-blue-50 rounded-2xl p-6 border-2 border-custom-yellow-200">
                                    <label class="block text-navy-700 font-bold mb-4 text-lg flex items-center">
                                        <i class="fas fa-tags mr-3 text-custom-yellow-600"></i>
                                        Kode Promo
                                    </label>
                                    <input name="promo_code" class="w-full p-4 border-2 border-navy-200 rounded-2xl focus:border-custom-blue-500 focus:ring-2 focus:ring-custom-blue-200 text-lg transition-all duration-300" placeholder="Masukkan kode promo (opsional)">
                                </div>
                            </div>
                        </div>

                        <!-- Total Price Display -->
                        <div class="bg-gradient-to-r from-navy-900 to-custom-blue-900 text-bone-50 p-8 border-t-4 border-custom-yellow-400">
                            <div class="max-w-3xl mx-auto text-center">
                                <h3 class="text-3xl font-black mb-4 flex items-center justify-center">
                                    <i class="fas fa-calculator mr-4 text-custom-yellow-400"></i>
                                    Total Harga: <span id="total-price" class="ml-4 text-custom-yellow-400">Rp 0</span>
                                </h3>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="bg-bone-50 p-8">
                            <div class="max-w-3xl mx-auto flex flex-col sm:flex-row gap-4 justify-between">
                                <button type="button" id="prev-btn" class="flex items-center justify-center px-8 py-4 bg-gradient-to-r from-navy-200 to-navy-300 text-navy-700 rounded-2xl font-bold text-lg transition-all duration-300 hover:from-navy-300 hover:to-navy-400 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl hover:scale-105" disabled>
                                    <i class="fas fa-arrow-left mr-3"></i>Sebelumnya
                                </button>

                                <div class="flex gap-4">
                                    <button type="button" id="next-btn" class="flex items-center justify-center px-8 py-4 bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 rounded-2xl font-bold text-lg transition-all duration-300 hover:from-custom-blue-700 hover:to-navy-800 shadow-lg hover:shadow-xl hover:scale-105">
                                        Selanjutnya<i class="fas fa-arrow-right ml-3"></i>
                                    </button>

                                    <button type="submit" id="submit-btn" class="hidden flex items-center justify-center px-8 py-4 bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 text-navy-900 rounded-2xl font-bold text-lg transition-all duration-300 hover:from-custom-yellow-600 hover:to-custom-yellow-700 shadow-lg hover:shadow-xl hover:scale-105">
                                        <i class="fas fa-shopping-cart mr-3"></i>Lanjut ke Pembayaran
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 4;
    let domainPrice = {{ $domainData['price'] }};
    let templatePrice = 0;
    let years = 1;
    let promoCode = '';

    function updateStep(step) {
        // Hide all step contents
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-${step}`).classList.remove('hidden');

        // Update step indicators
        document.querySelectorAll('.steps .step').forEach((el, idx) => {
            el.classList.remove('active');
            el.classList.remove('bg-gradient-to-r', 'from-custom-blue-600', 'to-navy-700', 'text-bone-50', 'shadow-lg');
            el.classList.add('bg-navy-100', 'text-navy-500');

            if (idx + 1 === step) {
                el.classList.add('active');
                el.classList.remove('bg-navy-100', 'text-navy-500');
                el.classList.add('bg-gradient-to-r', 'from-custom-blue-600', 'to-navy-700', 'text-bone-50', 'shadow-lg');
            }
        });

        // Update navigation buttons
        document.getElementById('prev-btn').disabled = step === 1;
        document.getElementById('next-btn').classList.toggle('hidden', step === totalSteps);
        document.getElementById('submit-btn').classList.toggle('hidden', step !== totalSteps);

        updateTotalPrice();
    }

    function updateTotalPrice() {
        let total = templatePrice + (domainPrice * years);
        if (promoCode === 'DISCOUNT10') {
            total *= 0.9;
        }
        document.getElementById('total-price').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // Navigation event listeners
    document.getElementById('next-btn').addEventListener('click', () => {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStep(currentStep);
        }
    });

    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStep(currentStep);
        }
    });

    // Template selection listener
    document.querySelectorAll('input[name="template_id"]').forEach(input => {
        input.addEventListener('change', (e) => {
            const priceText = e.target.closest('label').querySelector('p').textContent;
            templatePrice = parseInt(priceText.split('Rp ')[1].replace(/\./g, ''));
            updateTotalPrice();
        });
    });

    // Years selection listener
    document.querySelector('select[name="years"]').addEventListener('change', (e) => {
        years = parseInt(e.target.value);
        updateTotalPrice();
    });

    // Promo code listener
    document.querySelector('input[name="promo_code"]').addEventListener('input', (e) => {
        promoCode = e.target.value;
        updateTotalPrice();
    });

    // Initialize
    updateStep(1);
</script>

<!-- Custom Styles -->
<style>
    /* Smooth transitions for all interactive elements */
    * {
        transition: all 0.3s ease;
    }

    /* Custom focus styles */
    input:focus, select:focus, textarea:focus {
        outline: none;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Button hover effects */
    button:hover:not(:disabled) {
        transform: translateY(-2px);
    }

    /* Step indicator animations */
    .step {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .step.active {
        transform: scale(1.05);
    }

    /* Form section animations */
    .step-content {
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive design improvements */
    @media (max-width: 768px) {
        .lg\:flex-row {
            flex-direction: column;
        }

        .lg\:w-1\/4 {
            width: 100%;
        }

        .lg\:w-3\/4 {
            width: 100%;
        }

        .sticky {
            position: relative;
        }
    }
</style>
@endsection
