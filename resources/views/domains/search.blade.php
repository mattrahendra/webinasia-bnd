@extends('layouts.app')

@section('title', 'Domain Search Results')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Domain Search</h1>
        <p class="text-gray-600">Find and register your perfect domain name</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <form id="domain-search-form" action="{{ route('domains.search') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <input
                        type="text"
                        id="domain-input"
                        name="domain"
                        value="{{ $domain ?? '' }}"
                        placeholder="Enter domain name"
                        class="w-full p-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                        autocomplete="off">
                </div>
                <div class="md:w-64">
                    <select name="extensions[]" multiple id="extensions-select" class="w-full p-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                        @foreach (['com', 'net', 'org', 'id', 'co.id', 'web.id'] as $ext)
                        <option value="{{ $ext }}" {{ in_array($ext, $extensions ?? ['com', 'net', 'org', 'id']) ? 'selected' : '' }}>
                            .{{ $ext }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple</p>
                </div>
                <button
                    type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>

        <!-- Quick Extension Buttons -->
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="text-sm text-gray-600">Quick select:</span>
            @foreach (['com', 'net', 'org', 'id', 'co.id', 'web.id'] as $ext)
            <button
                type="button"
                class="quick-ext-btn px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-100 transition-colors"
                data-ext="{{ $ext }}">
                .{{ $ext }}
            </button>
            @endforeach
        </div>

        <!-- Loading Indicator -->
        <div id="search-loading" class="hidden text-center py-6">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Searching domains...</p>
        </div>
    </div>

    <!-- Results Section -->
    @if (isset($results) || true)
    <div id="results-section">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">
                @if(isset($domain))
                Results for "{{ $domain }}"
                @else
                Search Results
                @endif
            </h2>
            @if(isset($results))
            <span class="text-gray-600">{{ count($results) }} domains found</span>
            @endif
        </div>

        <div id="results-container">
            @if(isset($results))
            @include('domains.partials.results', ['results' => $results])
            @endif
        </div>
    </div>
    @endif

    <!-- Suggestions Section -->
    <div id="suggestions-section" class="mt-12 hidden">
        <h3 class="text-xl font-semibold mb-4">Alternative Suggestions</h3>
        <div id="suggestions-container" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </div>

    <!-- Help Section -->
    <div class="mt-12 bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-3">Domain Search Tips</h3>
        <ul class="space-y-2 text-sm text-gray-700">
            <li><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Keep it short and memorable</li>
            <li><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Avoid hyphens and numbers if possible</li>
            <li><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Consider multiple extensions (.com, .net, .org)</li>
            <li><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>Check trademark conflicts before registering</li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('domain-search-form');
        const input = document.getElementById('domain-input');
        const loading = document.getElementById('search-loading');
        const resultsContainer = document.getElementById('results-container');
        const suggestionsSection = document.getElementById('suggestions-section');
        const suggestionsContainer = document.getElementById('suggestions-container');

        let searchTimeout;

        // Realtime search
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 500);
            } else {
                resultsContainer.innerHTML = '';
                suggestionsSection.classList.add('hidden');
            }
        });

        // Form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = input.value.trim();
            if (query) {
                performSearch(query);
            }
        });

        // Quick extension buttons
        document.querySelectorAll('.quick-ext-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const ext = this.dataset.ext;
                const select = document.getElementById('extensions-select');

                // Toggle selection
                Array.from(select.options).forEach(option => {
                    if (option.value === ext) {
                        option.selected = !option.selected;
                    }
                });

                // Update button appearance
                this.classList.toggle('bg-blue-100');
                this.classList.toggle('border-blue-500');

                // Perform search if domain is entered
                const query = input.value.trim();
                if (query) {
                    performSearch(query);
                }
            });
        });

        function performSearch(query) {
            showLoading(true);

            const extensions = Array.from(document.getElementById('extensions-select').selectedOptions)
                .map(option => option.value);

            const formData = new FormData();
            formData.append('domain', query);
            extensions.forEach(ext => formData.append('extensions[]', ext));

            fetch('{{ route("domains.search") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showLoading(false);

                    if (data.success && data.results) {
                        displayResults(data.results);

                        // Load suggestions if no available domains
                        const hasAvailable = data.results.some(r => r.available);
                        if (!hasAvailable) {
                            loadSuggestions(query);
                        }
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showLoading(false);
                });
        }

        function displayResults(domains) {
            resultsContainer.innerHTML = '';

            domains.forEach(domain => {
                const resultDiv = document.createElement('div');
                resultDiv.className = `bg-white border-2 rounded-lg p-4 mb-3 flex justify-between items-center transition-all hover:shadow-md ${domain.available ? 'border-green-200 hover:border-green-300' : 'border-red-200'}`;

                resultDiv.innerHTML = `
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-lg font-semibold">${domain.domain}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${domain.available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${domain.available ? 'Available' : 'Taken'}
                                </span>
                            </div>
                            ${domain.available ? `
                                <div class="mt-1">
                                    <span class="text-xl font-bold text-blue-600">$${domain.price}</span>
                                    <span class="text-sm text-gray-500 ml-2">+ $${domain.renewal_price}/year renewal</span>
                                </div>
                            ` : `
                                <p class="text-sm text-gray-500 mt-1">This domain is already registered</p>
                            `}
                        </div>
                        ${domain.available ? `
                            <div class="flex space-x-2">
                                <form action="{{ route('domains.reserve') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="domain_name" value="${domain.domain.split('.')[0]}">
                                    <input type="hidden" name="extension" value="${domain.extension}">
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                        <i class="fas fa-shopping-cart mr-1"></i>Order Now
                                    </button>
                                </form>
                                <button type="button" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors" onclick="addToWishlist('${domain.domain}')">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        ` : ''}
                    `;

                resultsContainer.appendChild(resultDiv);
            });
        }

        function loadSuggestions(query) {
            fetch(`{{ route('domains.suggestions') }}?domain=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.suggestions) {
                        displaySuggestions(data.suggestions);
                        suggestionsSection.classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Suggestions error:', error));
        }

        function displaySuggestions(suggestions) {
            suggestionsContainer.innerHTML = '';

            suggestions.slice(0, 6).forEach(suggestion => {
                const suggestionDiv = document.createElement('div');
                suggestionDiv.className = 'bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer';
                suggestionDiv.onclick = () => {
                    input.value = suggestion.domain.split('.')[0];
                    performSearch(suggestion.domain.split('.')[0]);
                };

                suggestionDiv.innerHTML = `
                        <div class="text-center">
                            <h5 class="font-semibold text-gray-800">${suggestion.domain}</h5>
                            <p class="text-blue-600 font-bold mt-1">${suggestion.price}</p>
                            <p class="text-xs text-gray-500 mt-1">Click to search</p>
                        </div>
                    `;

                suggestionsContainer.appendChild(suggestionDiv);
            });
        }

        function showLoading(show) {
            if (show) {
                loading.classList.remove('hidden');
                resultsContainer.style.opacity = '0.5';
            } else {
                loading.classList.add('hidden');
                resultsContainer.style.opacity = '1';
            }
        }

        function addToWishlist(domain) {
            // Implement wishlist functionality
            alert(`Added ${domain} to wishlist!`);
        }
    });
</script>
@endsection
