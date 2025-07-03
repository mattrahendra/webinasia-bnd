@extends('layouts.app')

@section('content')
<div class="flex max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="w-1/4 pr-4">
        <div class="steps space-y-4">
            <div class="step active text-lg font-semibold text-gray-800">1. Domain</div>
            <div class="step text-lg font-semibold text-gray-500">2. Template</div>
            <div class="step text-lg font-semibold text-gray-500">3. Data Diri</div>
            <div class="step text-lg font-semibold text-gray-500">4. Langganan & Add-ons</div>
        </div>
    </div>

    <div class="w-3/4 bg-white p-6 rounded-lg shadow-md">
        <form id="order-form" method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="step-content" id="step-1">
                <h2 class="text-xl font-bold mb-4">Domain Selection</h2>
                <p class="mb-2">Domain: {{ $domainData['full_domain'] }}</p>
                <p class="mb-4">Harga: Rp {{ number_format($domainData['price'], 0, ',', '.') }} / tahun</p>
                <input type="hidden" name="domain_name" value="{{ $domainData['domain_name'] }}">
                <input type="hidden" name="extension" value="{{ $domainData['extension'] }}">
                <input type="hidden" name="domain_price" value="{{ $domainData['price'] }}">
            </div>

            <div class="step-content hidden" id="step-2">
                <h2 class="text-xl font-bold mb-4">Pilih Template</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($templates as $template)
                        <div class="border p-4 rounded-lg">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="template_id" value="{{ $template->id }}" required>
                                <span>{{ $template->name }} - Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                            </label>
                            <a href="{{ route('templates.preview', $template) }}" target="_blank" class="text-blue-600 hover:underline">Preview</a>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="step-content hidden" id="step-3">
                <h2 class="text-xl font-bold mb-4">Data Diri</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <input name="name" class="w-full p-2 border rounded" placeholder="Nama Lengkap" required>
                        <input name="email" type="email" class="w-full p-2 border rounded" placeholder="Email" required>
                    </div>
                    <input name="phone" class="w-full p-2 border rounded" placeholder="Nomor HP" required>

                    <div class="mt-4">
                        <h3 class="font-semibold mb-2">Alamat (Opsional)</h3>
                        <div class="space-y-2">
                            <textarea name="address" class="w-full p-2 border rounded" placeholder="Alamat lengkap" rows="2"></textarea>
                            <div class="grid grid-cols-2 gap-4">
                                <input name="city" class="w-full p-2 border rounded" placeholder="Kota">
                                <input name="postal_code" class="w-full p-2 border rounded" placeholder="Kode Pos">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-content hidden" id="step-4">
                <h2 class="text-xl font-bold mb-4">Langganan & Add-ons</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block mb-1">Durasi Langganan</label>
                        <select name="years" class="w-full p-2 border rounded" required>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} tahun</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full p-2 border rounded" required>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="e_wallet">E-Wallet (GoPay)</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Kode Promo</label>
                        <input name="promo_code" class="w-full p-2 border rounded" placeholder="Masukkan kode promo">
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                <h3 class="text-lg font-semibold">Total Harga: <span id="total-price">Rp 0</span></h3>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="button" id="prev-btn" class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50" disabled>Sebelumnya</button>
                <button type="button" id="next-btn" class="px-4 py-2 bg-blue-600 text-white rounded">Selanjutnya</button>
                <button type="submit" id="submit-btn" class="px-4 py-2 bg-green-600 text-white rounded hidden">Lanjut ke Pembayaran</button>
            </div>
        </form>
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
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-${step}`).classList.remove('hidden');

        document.querySelectorAll('.steps .step').forEach((el, idx) => {
            el.classList.remove('active', 'text-gray-800');
            el.classList.add('text-gray-500');
            if (idx + 1 === step) {
                el.classList.add('active', 'text-gray-800');
            }
        });

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

    document.querySelectorAll('input[name="template_id"]').forEach(input => {
        input.addEventListener('change', (e) => {
            templatePrice = parseInt(e.target.nextElementSibling.textContent.split('Rp ')[1].replace(/\./g, ''));
            updateTotalPrice();
        });
    });

    document.querySelector('select[name="years"]').addEventListener('change', (e) => {
        years = parseInt(e.target.value);
        updateTotalPrice();
    });

    document.querySelector('input[name="promo_code"]').addEventListener('input', (e) => {
        promoCode = e.target.value;
        updateTotalPrice();
    });

    updateStep(1);
</script>
@endsection
