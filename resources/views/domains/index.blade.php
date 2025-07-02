@extends('layouts.app')

@section('title', 'Registrasi Domain')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Temukan Domain Sempurna Anda</h1>
        <p class="text-xl text-gray-600">Mulai perjalanan online Anda dengan nama domain yang tepat</p>
    </div>

    <!-- Search Section -->
    <div class="bg-white shadow-lg rounded-lg p-8 mb-12">
        <form id="domain-search-form" class="space-y-4">
            @csrf
            <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-4">
                <div class="flex-1">
                    <input
                        type="text"
                        id="domain-input"
                        name="domain"
                        placeholder="Masukkan nama domain Anda (tanpa ekstensi)"
                        class="w-full p-4 text-lg border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none transition-colors"
                        autocomplete="off">
                    <p class="text-sm text-gray-500 mt-1">Contoh: tokokeren, bisnisku, namaperusahaan</p>
                </div>
                <div class="lg:w-64">
                    <select name="extensions[]" multiple id="extensions-select"
                        class="w-full p-4 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        size="1">
                        <option value="com" selected>.com</option>
                        <option value="net" selected>.net</option>
                        <option value="org" selected>.org</option>
                        <option value="id" selected>.id</option>
                        <option value="co.id" selected>.co.id</option>
                        <option value="web.id" selected>.web.id</option>
                        <option value="info">.info</option>
                        <option value="biz">.biz</option>
                        <option value="co">.co</option>
                        <option value="me">.me</option>
                        <option value="tv">.tv</option>
                        <option value="io">.io</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Tahan Ctrl untuk pilih beberapa ekstensi</p>
                </div>
                <button
                    type="submit"
                    class="px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </form>

        <!-- Loading Indicator -->
        <div id="search-loading" class="hidden text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <p class="mt-4 text-gray-600 text-lg">Mencari domain tersedia...</p>
        </div>

        <!-- Search Results -->
        <div id="search-results" class="mt-8 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold">Hasil Pencarian</h3>
                <div id="results-summary" class="text-sm text-gray-600"></div>
            </div>
            <div id="results-container"></div>
        </div>
    </div>

    <!-- Featured Domains Section -->
    @if(!empty($featuredDomains) && is_array($featuredDomains))
    <div class="mb-12">
        <h2 class="text-3xl font-bold text-center mb-8">Ekstensi Domain Populer</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredDomains as $domain)
            <div class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-lg transition-shadow border border-gray-100
                    {{ ($domain['popular'] ?? false) ? 'ring-2 ring-blue-200 bg-blue-50' : '' }}">
                @if($domain['popular'] ?? false)
                <div class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full inline-block mb-2">
                    POPULER
                </div>
                @endif
                <h3 class="text-2xl font-bold text-blue-600 mb-2">.{{ $domain['extension'] }}</h3>
                <p class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $domain['price_formatted'] ?? 'Rp ' . number_format(($domain['price_idr'] ?? 199000), 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-600 mb-4">{{ $domain['description'] ?? 'Ekstensi domain' }}</p>
                <div class="text-xs text-gray-500">
                    Pembaruan: {{ $domain['price_formatted'] ?? 'Rp ' . number_format(($domain['price_idr'] ?? 199000), 0, ',', '.') }}/tahun
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Search Popular Domains -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-8 mb-12 text-white">
        <h2 class="text-2xl font-bold text-center mb-6">Cari Cepat Domain Populer</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $popularExtensions = ['com', 'id', 'co.id', 'net'];
            @endphp
            @foreach($popularExtensions as $ext)
            @php
            $featured = is_array($featuredDomains) ? collect($featuredDomains)->firstWhere('extension', $ext) : null;
            @endphp
            @if($featured)
            <button onclick="quickSearch('{{ $ext }}')"
                class="bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg p-4 transition-all text-center">
                <div class="font-bold text-lg">.{{ $ext }}</div>
                <div class="text-sm opacity-90">{{ $featured['price_formatted'] ?? 'Rp ' . number_format(($featured['price_idr'] ?? 199000), 0, ',', '.') }}</div>
            </button>
            @endif
            @endforeach
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="bg-gray-50 rounded-lg p-8">
        <h2 class="text-3xl font-bold text-center mb-8">Mengapa Memilih Layanan Domain Kami?</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Aman & Terpercaya</h3>
                <p class="text-gray-600">Domain Anda dilindungi dengan fitur keamanan canggih dan jaminan uptime 99.9%.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Harga Kompetitif</h3>
                <p class="text-gray-600">Dapatkan harga terbaik untuk registrasi domain tanpa biaya tersembunyi.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Dukungan 24/7</h3>
                <p class="text-gray-600">Tim dukungan ahli kami selalu siap membantu kebutuhan domain Anda.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('domain-search-form');
        const input = document.getElementById('domain-input');
        const loading = document.getElementById('search-loading');
        const results = document.getElementById('search-results');
        const resultsContainer = document.getElementById('results-container');
        const resultsSummary = document.getElementById('results-summary');
        const extensionsSelect = document.getElementById('extensions-select');

        let searchTimeout;

        // Make extensions select more user-friendly
        extensionsSelect.size = 3;
        extensionsSelect.style.height = 'auto';

        // Realtime search while typing
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 800); // Increased delay to reduce API calls
            } else {
                results.classList.add('hidden');
            }
        });

        // Search on form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = input.value.trim();
            if (query) {
                performSearch(query);
            }
        });

        function performSearch(query) {
            // Clean domain input (remove dots, spaces, special chars)
            query = query.toLowerCase().replace(/[^a-z0-9-]/g, '');

            if (!query) {
                showError('Nama domain tidak valid');
                return;
            }

            loading.classList.remove('hidden');
            results.classList.add('hidden');

            const extensions = Array.from(extensionsSelect.selectedOptions)
                .map(option => option.value);

            if (extensions.length === 0) {
                showError('Pilih minimal satu ekstensi domain');
                loading.classList.add('hidden');
                return;
            }

            const formData = new FormData();
            formData.append('domain', query);
            extensions.forEach(ext => formData.append('extensions[]', ext));
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            fetch('{{ route("domains.search") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    loading.classList.add('hidden');

                    if (data.success && data.results && data.results.length > 0) {
                        displayResults(data.results, query);
                        results.classList.remove('hidden');
                    } else {
                        showError('Tidak ada hasil ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    loading.classList.add('hidden');
                    showError('Pencarian gagal. Silakan coba lagi.');
                });
        }

        function displayResults(domains, searchQuery) {
            resultsContainer.innerHTML = '';

            if (!domains || domains.length === 0) {
                resultsContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak ada hasil</h3>
                    <p class="text-gray-600">Coba nama domain atau ekstensi yang berbeda.</p>
                </div>
            `;
                return;
            }

            // Count available and taken domains
            const available = domains.filter(d => d.available).length;
            const taken = domains.length - available;

            resultsSummary.innerHTML = `
            <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>${available} Tersedia</span>
            <span class="text-red-600 ml-4"><i class="fas fa-times-circle mr-1"></i>${taken} Sudah Diambil</span>
        `;

            domains.forEach(domain => {
                const resultDiv = document.createElement('div');
                const isAvailable = domain.available;
                const domainName = domain.domain;
                const extension = domain.extension;

                resultDiv.className = `bg-white border-2 rounded-lg p-4 mb-3 transition-all hover:shadow-md ${
                isAvailable ? 'border-green-200 hover:border-green-300' : 'border-red-200'
            }`;

                if (isAvailable) {
                    resultDiv.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold">${domainName}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div>
                                    <span class="text-xl font-bold text-blue-600">${domain.price_formatted}</span>
                                    <span class="text-sm text-gray-500 ml-2">+ ${domain.price_formatted}/tahun pembaruan</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <form action="{{ route('domains.reserve') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="domain_name" value="${searchQuery}">
                                <input type="hidden" name="extension" value="${extension}">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm">
                                    <i class="fas fa-shopping-cart mr-1"></i>Pesan Sekarang
                                </button>
                            </form>
                            <button type="button"
                                    class="px-3 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors"
                                    onclick="toggleWishlist('${domainName}', this)"
                                    title="Tambah ke wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                `;
                } else {
                    resultDiv.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-semibold text-gray-600">${domainName}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Sudah Diambil
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>Domain ini sudah terdaftar
                            </p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button type="button"
                                    class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors text-sm"
                                    onclick="checkWhois('${domainName}')">
                                <i class="fas fa-search mr-1"></i>WHOIS
                            </button>
                            <button type="button"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm"
                                    onclick="notifyWhenAvailable('${domainName}')">
                                <i class="fas fa-bell mr-1"></i>Beritahu Saya
                            </button>is
                        </div>
                    </div>
                `;
                }

                resultsContainer.appendChild(resultDiv);
            });
        }

        function showError(message) {
            resultsContainer.innerHTML = `
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <p class="text-red-700 font-medium">${message}</p>
            </div>
        `;
            resultsSummary.innerHTML = '';
            results.classList.remove('hidden');
        }

        // Quick search function
        window.quickSearch = function(extension) {
            const randomNames = ['tokoku', 'bisnisku', 'kedaiku', 'usahaku', 'proyekku', 'ideaku', 'karyaku'];
            const randomName = randomNames[Math.floor(Math.random() * randomNames.length)];

            input.value = randomName;

            // Select only the clicked extension
            Array.from(extensionsSelect.options).forEach(option => {
                option.selected = option.value === extension;
            });

            performSearch(randomName);
        };

        // Wishlist functionality
        window.toggleWishlist = function(domain, button) {
            const icon = button.querySelector('i');
            const isWishlisted = icon.classList.contains('fas');

            if (isWishlisted) {
                icon.classList.remove('fas', 'fa-heart', 'text-red-500');
                icon.classList.add('far', 'fa-heart');
                button.title = 'Tambah ke wishlist';
            } else {
                icon.classList.remove('far', 'fa-heart');
                icon.classList.add('fas', 'fa-heart', 'text-red-500');
                button.title = 'Hapus dari wishlist';
            }

            const action = isWishlisted ? 'dihapus dari' : 'ditambah ke';
            showNotification(`${domain} ${action} wishlist`, isWishlisted ? 'info' : 'success');
        };

        // WHOIS check
        window.checkWhois = function(domain) {
            window.open(`https://who.is/whois/${domain}`, '_blank');
        };

        // Notify when available
        window.notifyWhenAvailable = function(domain) { 
            const email = prompt(`Masukkan email Anda untuk diberitahu ketika ${domain} tersedia:`);
            if (email && email.includes('@')) {
                showNotification(`Kami akan memberitahu Anda di ${email} ketika ${domain} tersedia`, 'success');
            } else if (email) {
                showNotification('Email tidak valid', 'error');
            }
        };

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }
    });
</script>
@endsection
