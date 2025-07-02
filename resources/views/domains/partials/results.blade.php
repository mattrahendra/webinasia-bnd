@if(count($results) > 0)
<div class="space-y-4">
    @foreach ($results as $result)
    <div class="bg-white border-2 rounded-lg p-6 transition-all hover:shadow-lg {{ $result['available'] ? 'border-green-200 hover:border-green-300 hover:bg-green-50' : 'border-red-200 hover:bg-red-50' }}">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <h4 class="text-xl font-bold text-gray-800">{{ $result['domain'] }}</h4>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $result['available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $result['available'] ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                        {{ $result['available'] ? 'Tersedia' : 'Sudah Diambil' }}
                    </span>
                    @if(isset($result['fallback']) && $result['fallback'])
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Demo
                    </span>
                    @endif
                </div>

                @if ($result['available'])
                <div class="flex items-center space-x-6 mb-2">
                    <div>
                        <span class="text-2xl font-bold text-blue-600">{{ $result['price_formatted'] ?? 'Rp ' . number_format(($result['price_idr'] ?? $result['price'] ?? 199000), 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-500 ml-2">untuk tahun pertama</span>
                    </div>
                    @if(isset($result['price_usd']))
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-dollar-sign mr-1"></i>${{ number_format($result['price_usd'], 2) }} USD
                    </div>
                    @endif
                </div>
                <div class="text-sm text-gray-600 mb-3">
                    <i class="fas fa-sync-alt mr-1"></i>Pembaruan: {{ $result['price_formatted'] ?? 'Rp ' . number_format(($result['price_idr'] ?? $result['price'] ?? 199000), 0, ',', '.') }}/tahun
                </div>

                {{-- Features untuk domain tersedia --}}
                <div class="flex flex-wrap gap-2 text-xs text-gray-600 mb-4">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                        <i class="fas fa-shield-alt mr-1"></i>Gratis Privacy Protection
                    </span>
                    <span class="bg-green-50 text-green-700 px-2 py-1 rounded-full">
                        <i class="fas fa-envelope mr-1"></i>Email Forwarding
                    </span>
                    <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded-full">
                        <i class="fas fa-globe mr-1"></i>DNS Management
                    </span>
                </div>
                @else
                <div class="mb-3">
                    <p class="text-gray-600 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-red-500"></i>
                        Domain ini sudah terdaftar dan tidak tersedia untuk registrasi
                    </p>
                </div>

                {{-- Suggestions untuk domain yang sudah diambil --}}
                <div class="bg-blue-50 p-3 rounded-lg mb-3">
                    <p class="text-sm text-blue-800 font-medium mb-2">
                        <i class="fas fa-lightbulb mr-1"></i>Saran Alternatif:
                    </p>
                    <div class="flex flex-wrap gap-2 text-xs">
                        @php
                        $baseDomain = explode('.', $result['domain'])[0];
                        $alternatives = [
                        $baseDomain . 'ku.' . $result['extension'],
                        'my' . $baseDomain . '.' . $result['extension'],
                        $baseDomain . 'online.' . ($result['extension'] === 'com' ? 'id' : 'com'),
                        $baseDomain . 'store.' . $result['extension']
                        ];
                        @endphp
                        @foreach(array_slice($alternatives, 0, 3) as $alt)
                        <button onclick="searchAlternative('{{ $alt }}')"
                            class="bg-white border border-blue-200 text-blue-700 px-2 py-1 rounded hover:bg-blue-100 transition-colors">
                            {{ $alt }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="ml-6 flex flex-col space-y-2">
                @if ($result['available'])
                <div class="flex items-center space-x-2">
                    {{-- Order Button --}}
                    <form action="{{ route('domains.reserve') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="domain_name" value="{{ explode('.', $result['domain'])[0] }}">
                        <input type="hidden" name="extension" value="{{ $result['extension'] }}">
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-shopping-cart mr-2"></i>Pesan Sekarang
                        </button>
                    </form>
                </div>
                <div class="flex items-center space-x-2">
                    {{-- Wishlist Button --}}
                    <button type="button"
                        class="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors flex-1"
                        onclick="toggleWishlist('{{ $result['domain'] }}', this)"
                        title="Tambah ke wishlist">
                        <i class="far fa-heart mr-1"></i>Wishlist
                    </button>

                    {{-- Info Button --}}
                    <button type="button"
                        class="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors"
                        onclick="showDomainInfo('{{ $result['domain'] }}')"
                        title="Informasi domain">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
                @else
                <div class="flex flex-col space-y-2">
                    {{-- WHOIS Button --}}
                    <button type="button"
                        class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium"
                        onclick="checkWhois('{{ $result['domain'] }}')">
                        <i class="fas fa-search mr-2"></i>Cek WHOIS
                    </button>

                    {{-- Notify Button --}}
                    <button type="button"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium"
                        onclick="notifyWhenAvailable('{{ $result['domain'] }}')">
                        <i class="fas fa-bell mr-2"></i>Beritahu Saya
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Enhanced Results Summary --}}
<div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Ringkasan Pencarian
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="text-center">
            <div class="text-3xl font-bold text-green-600 mb-1">
                {{ collect($results)->where('available', true)->count() }}
            </div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <i class="fas fa-check-circle mr-1 text-green-500"></i>Domain Tersedia
            </div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-red-600 mb-1">
                {{ collect($results)->where('available', false)->count() }}
            </div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <i class="fas fa-times-circle mr-1 text-red-500"></i>Sudah Diambil
            </div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-blue-600 mb-1">
                {{ count($results) }}
            </div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <i class="fas fa-globe mr-1 text-blue-500"></i>Total Dicek
            </div>
        </div>
    </div>

    @if(collect($results)->where('available', true)->isNotEmpty())
    <div class="mt-4 p-4 bg-white rounded-lg border border-blue-200">
        <p class="text-sm text-center text-gray-700">
            <i class="fas fa-clock mr-1 text-orange-500"></i>
            <strong>Tips:</strong> Domain populer cepat habis! Pesan sekarang untuk mengamankan pilihan Anda.
        </p>
    </div>
    @endif
</div>

{{-- Alternative Search Suggestions --}}
@if(collect($results)->where('available', true)->isEmpty())
<div class="mt-6 bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
    <h4 class="font-semibold text-yellow-800 mb-3">
        <i class="fas fa-lightbulb mr-2"></i>Tidak menemukan domain yang tepat?
    </h4>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @php
        $suggestions = [
        'Coba variasi nama yang berbeda',
        'Pertimbangkan ekstensi lain (.id, .co, .me)',
        'Tambahkan kata seperti "ku", "online", "store"',
        'Gunakan sinonim atau singkatan',
        'Coba domain dengan tanda hubung (-)',
        'Pertimbangkan domain internasional'
        ];
        @endphp
        @foreach($suggestions as $suggestion)
        <div class="flex items-start space-x-2 text-sm text-yellow-800">
            <i class="fas fa-arrow-right text-yellow-600 mt-0.5 text-xs"></i>
            <span>{{ $suggestion }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

@else
{{-- No Results Found --}}
<div class="text-center py-16">
    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-search text-gray-400 text-3xl"></i>
    </div>
    <h3 class="text-2xl font-semibold text-gray-800 mb-3">Tidak Ada Hasil Ditemukan</h3>
    <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Maaf, pencarian Anda tidak menghasilkan domain apapun.
        Coba gunakan kata kunci yang berbeda atau ekstensi domain lainnya.
    </p>

    <div class="flex justify-center space-x-4">
        <button onclick="clearSearch()"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <i class="fas fa-redo mr-2"></i>Cari Lagi
        </button>
        <button onclick="showPopularDomains()"
            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
            <i class="fas fa-star mr-2"></i>Lihat Domain Populer
        </button>
    </div>
</div>
@endif

<script>
    // Existing functions enhanced
    function toggleWishlist(domain, button) {
        const icon = button.querySelector('i');
        const text = button.querySelector('span') || button;
        const isWishlisted = icon.classList.contains('fas');

        if (isWishlisted) {
            icon.classList.remove('fas', 'fa-heart', 'text-red-500');
            icon.classList.add('far', 'fa-heart');
            button.title = 'Tambah ke wishlist';
            button.classList.remove('bg-red-50', 'border-red-200', 'text-red-700');
            button.classList.add('border-gray-300', 'text-gray-600');
        } else {
            icon.classList.remove('far', 'fa-heart');
            icon.classList.add('fas', 'fa-heart', 'text-red-500');
            button.title = 'Hapus dari wishlist';
            button.classList.remove('border-gray-300', 'text-gray-600');
            button.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
        }

        const action = isWishlisted ? 'dihapus dari' : 'ditambah ke';
        showNotification(`${domain} ${action} wishlist`, isWishlisted ? 'info' : 'success');
    }

    function showDomainInfo(domain) {
        const info = `
        <div class="text-left">
            <h3 class="font-bold mb-2">Informasi ${domain}</h3>
            <ul class="space-y-1 text-sm">
                <li>• Gratis Privacy Protection</li>
                <li>• Email Forwarding tidak terbatas</li>
                <li>• DNS Management</li>
                <li>• Dukungan 24/7</li>
                <li>• Auto-renewal tersedia</li>
            </ul>
        </div>
    `;

        // Create custom modal here or use existing modal system
        alert(`Domain: ${domain}\n\nFeatures:\n- Free Privacy Protection\n- Email Forwarding\n- DNS Management\n- 24/7 Support\n- Auto-renewal available`);
    }

    function checkWhois(domain) {
        window.open(`https://whois.net/whois/${domain}`, '_blank');
    }

    function notifyWhenAvailable(domain) {
        const email = prompt(`Masukkan email Anda untuk diberitahu ketika ${domain} tersedia:`);
        if (email && email.includes('@')) {
            showNotification(`Kami akan memberitahu Anda di ${email} ketika ${domain} tersedia`, 'success');
            // AJAX call to save notification request would go here
        } else if (email) {
            showNotification('Format email tidak valid', 'error');
        }
    }

    function searchAlternative(domain) {
        const parts = domain.split('.');
        const domainName = parts[0];
        const extension = parts[1];

        // Trigger search for alternative domain
        if (typeof performSearch === 'function') {
            document.getElementById('domain-input').value = domainName;

            // Update extension selection
            const extensionsSelect = document.getElementById('extensions-select');
            Array.from(extensionsSelect.options).forEach(option => {
                option.selected = option.value === extension;
            });

            performSearch(domainName);
        }
    }

    function clearSearch() {
        document.getElementById('domain-input').value = '';
        document.getElementById('search-results').classList.add('hidden');
    }

    function showPopularDomains() {
        const popularDomains = ['toko', 'bisnis', 'online', 'store', 'shop', 'web'];
        const randomDomain = popularDomains[Math.floor(Math.random() * popularDomains.length)];

        document.getElementById('domain-input').value = randomDomain;
        if (typeof performSearch === 'function') {
            performSearch(randomDomain);
        }
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'info': 'bg-blue-500',
            'warning': 'bg-yellow-500'
        };

        notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 text-white transform transition-all duration-300 ${bgColor[type] || bgColor.info}`;
        notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remove after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }
</script>
