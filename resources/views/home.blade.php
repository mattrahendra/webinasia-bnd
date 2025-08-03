@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section with Domain Search -->
    <section class="relative overflow-hidden bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50 py-32">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23fbbf24" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center max-w-5xl mx-auto">
                <div class="mb-8">
                    <i class="fas fa-globe text-7xl text-custom-yellow-400 mb-6 animate-pulse"></i>
                </div>
                <h1 class="text-6xl md:text-8xl font-black mb-8 bg-gradient-to-r from-bone-50 via-custom-blue-200 to-custom-yellow-300 bg-clip-text text-transparent leading-tight">
                    Find Your Perfect Domain
                </h1>
                <p class="text-2xl md:text-3xl mb-16 text-custom-blue-200 max-w-3xl mx-auto font-light">
                    Secure your online presence with our premium domains and professional templates
                </p>

                <!-- Domain Search Form -->
                <div class="max-w-4xl mx-auto mb-12">
                    <form action="{{ route('domains.search') }}" method="GET" class="flex flex-col sm:flex-row gap-6 sm:gap-0">
                        <div class="relative flex-grow">
                            <i class="fas fa-search absolute left-6 top-1/2 transform -translate-y-1/2 text-navy-400 text-xl"></i>
                            <input type="text" name="domain" placeholder="Enter your dream domain name..."
                                class="w-full pl-16 pr-6 py-6 rounded-2xl sm:rounded-r-none sm:rounded-l-2xl border-0 focus:ring-4 focus:ring-custom-yellow-400/50 text-navy-800 text-xl shadow-2xl bg-bone-50 placeholder-navy-400 font-medium"
                                required>
                        </div>
                        <button type="submit"
                            class="bg-gradient-to-r from-custom-yellow-400 via-custom-yellow-500 to-custom-yellow-600 text-navy-900 px-10 py-6 rounded-2xl sm:rounded-l-none sm:rounded-r-2xl hover:from-custom-yellow-500 hover:to-custom-yellow-700 font-bold text-xl shadow-2xl transform hover:scale-105 transition-all duration-300 hover:shadow-yellow-glow">
                            <i class="fa-solid fa-rocket mr-3"></i>Search Now
                        </button>
                    </form>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap justify-center items-center gap-12 text-custom-blue-200 mb-16">
                    <div class="flex items-center group">
                        <i class="fas fa-shield-alt mr-3 text-2xl group-hover:text-custom-yellow-400 transition-colors duration-300"></i>
                        <span class="text-lg font-medium">Secure Registration</span>
                    </div>
                    <div class="flex items-center group">
                        <i class="fas fa-clock mr-3 text-2xl group-hover:text-custom-yellow-400 transition-colors duration-300"></i>
                        <span class="text-lg font-medium">Instant Activation</span>
                    </div>
                    <div class="flex items-center group">
                        <i class="fas fa-headset mr-3 text-2xl group-hover:text-custom-yellow-400 transition-colors duration-300"></i>
                        <span class="text-lg font-medium">24/7 Support</span>
                    </div>
                </div>
            </div>

            <!-- Featured Domains -->
            <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                @foreach($featuredDomains as $domain)
                <div class="bg-bone-50/10 backdrop-blur-lg border border-custom-blue-400/30 rounded-3xl p-8 text-center hover:bg-bone-50/20 transition-all duration-500 group hover:-translate-y-2 hover:shadow-2xl">
                    <div class="text-5xl mb-6 group-hover:scale-125 transition-transform duration-500">
                        <i class="fas fa-dot-circle text-custom-yellow-400"></i>
                    </div>
                    <h3 class="text-3xl font-bold mb-4 text-bone-50">.{!! $domain['extension'] !!}</h3>
                    <p class="text-custom-blue-200 mb-6 text-base leading-relaxed">{{ $domain['description'] }}</p>
                    <p class="text-custom-yellow-400 font-bold text-2xl mb-8">{{ $domain['price_formatted'] }}</p>
                    <a href="{{ route('domains.search') }}?domain=example&extensions[]={{ $domain['extension'] }}"
                        class="inline-flex items-center bg-gradient-to-r from-bone-50 to-custom-blue-100 text-navy-900 px-8 py-4 rounded-2xl hover:from-custom-yellow-400 hover:to-custom-yellow-500 font-bold transition-all duration-300 hover:scale-110 shadow-xl group-hover:shadow-yellow-glow">
                        <i class="fas fa-check mr-3"></i>Check Availability
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Templates Section -->
    <section class="py-32 bg-bone-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-custom-blue-600 to-navy-700 rounded-full mb-8">
                    <i class="fas fa-palette text-bone-50 text-3xl"></i>
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-navy-900 mb-6">Professional Templates</h2>
                <p class="text-2xl text-navy-600 max-w-3xl mx-auto font-light">Choose from our collection of stunning, responsive templates designed to make your website stand out</p>
            </div>

            <!-- Category Tabs -->
            <div class="mb-16">
                <div class="border-b border-navy-200">
                    <nav class="-mb-px flex space-x-12 overflow-x-auto justify-center">
                        <!-- All Categories Tab -->
                        <a href="{{ route('templates.index', ['category_id' => 'all']) }}"
                            class="group py-6 px-8 border-b-4 font-bold text-lg whitespace-nowrap border-transparent text-navy-500 hover:text-custom-blue-600 hover:border-custom-blue-400 transition-all duration-300">
                            <i class="fas fa-th-large mr-3 group-hover:scale-125 transition-transform duration-300"></i>
                            All Templates
                            <span class="ml-3 bg-gradient-to-r from-custom-blue-100 to-custom-yellow-100 text-navy-800 py-2 px-4 rounded-full text-sm font-bold">
                                {{ \App\Models\Template::count() }}
                            </span>
                        </a>

                        <!-- Category Tabs -->
                        @foreach($categories as $category)
                        <a href="{{ route('templates.index', ['category_id' => $category->id]) }}"
                            class="group py-6 px-8 border-b-4 font-bold text-lg whitespace-nowrap border-transparent text-navy-500 hover:text-custom-blue-600 hover:border-custom-blue-400 transition-all duration-300">
                            <i class="fas fa-folder mr-3 group-hover:scale-125 transition-transform duration-300"></i>
                            {{ $category->name }}
                            <span class="ml-3 bg-gradient-to-r from-custom-blue-100 to-custom-yellow-100 text-navy-800 py-2 px-4 rounded-full text-sm font-bold">
                                {{ $category->templates_count }}
                            </span>
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Templates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-16">
                @foreach($featuredTemplates as $template)
                <div class="group bg-bone-50 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 overflow-hidden border-2 border-navy-100 hover:border-custom-blue-300 hover:-translate-y-3 hover:scale-105">
                    <!-- Template Image -->
                    <div class="relative h-64 bg-gradient-to-br from-navy-100 to-custom-blue-100 overflow-hidden">
                        @if($template->images && $template->images->count() > 0)
                        <img src="{{ Storage::url($template->images->first()->image_path) }}"
                            alt="{{ $template->name }}"
                            class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-custom-blue-100 to-custom-yellow-100">
                            <i class="fas fa-image text-6xl text-custom-blue-400 group-hover:scale-125 transition-transform duration-500"></i>
                        </div>
                        @endif

                        <!-- Category Badge -->
                        @if($template->category)
                        <div class="absolute top-6 left-6">
                            <span class="inline-flex items-center bg-bone-50/95 backdrop-blur-sm text-navy-700 text-sm font-bold px-4 py-3 rounded-full border-2 border-custom-blue-200 shadow-lg">
                                <i class="fas fa-tag mr-2"></i>
                                {{ $template->category->name }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Template Info -->
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-navy-900 mb-4 line-clamp-2 group-hover:text-custom-blue-600 transition-colors duration-300">
                            {{ $template->name }}
                        </h3>

                        <p class="text-navy-600 text-base mb-8 line-clamp-3 leading-relaxed">
                            {{ $template->description ?? 'No description available' }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            @if($template->projects && $template->projects->where('preview_path', '!=', null)->count() > 0)
                            <a href="{{ route('templates.preview', $template) }}"
                                class="flex-1 bg-gradient-to-r from-navy-100 to-custom-blue-100 text-navy-700 px-6 py-4 rounded-2xl text-base font-bold hover:from-navy-200 hover:to-custom-blue-200 transition-all duration-300 text-center group/btn hover:scale-110 shadow-lg"
                                target="_blank">
                                <i class="fas fa-eye mr-3 group-hover/btn:scale-125 transition-transform duration-300"></i>Live Preview
                            </a>
                            @else
                            <a href="{{ route('templates.show', $template) }}"
                                class="flex-1 bg-gradient-to-r from-navy-100 to-custom-blue-100 text-navy-700 px-6 py-4 rounded-2xl text-base font-bold hover:from-navy-200 hover:to-custom-blue-200 transition-all duration-300 text-center group/btn hover:scale-110 shadow-lg">
                                <i class="fas fa-info-circle mr-3 group-hover/btn:scale-125 transition-transform duration-300"></i>View Details
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- View All Templates Button -->
            <div class="text-center">
                <a href="{{ route('templates.index') }}"
                    class="inline-flex items-center px-12 py-6 bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 font-black text-xl rounded-3xl hover:from-custom-blue-700 hover:to-navy-800 transition-all duration-300 hover:scale-110 shadow-2xl hover:shadow-glow">
                    <i class="fas fa-th-large mr-4 text-2xl"></i>Explore All Templates
                    <i class="fas fa-arrow-right ml-4 text-xl"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-32 bg-gradient-to-br from-navy-50 to-custom-blue-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 rounded-full mb-8">
                    <i class="fas fa-dollar-sign text-bone-50 text-3xl"></i>
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-navy-900 mb-6">Simple, Transparent Pricing</h2>
                <p class="text-2xl text-navy-600 max-w-3xl mx-auto font-light">Choose the perfect domain extension for your project with our competitive pricing</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-7xl mx-auto">
                @foreach($pricingPackages as $package)
                <div class="group relative bg-bone-50 rounded-3xl shadow-xl hover:shadow-2xl p-10 text-center border-2 border-navy-100 hover:border-custom-blue-300 transition-all duration-700 hover:-translate-y-3 hover:scale-105">
                    <!-- Popular Badge -->
                    @if($loop->index == 1)
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
                        <span class="bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 text-bone-50 text-base font-black px-6 py-3 rounded-full shadow-xl border-2 border-custom-yellow-400">
                            <i class="fas fa-star mr-2"></i>Most Popular
                        </span>
                    </div>
                    @endif

                    <div class="text-6xl mb-8 group-hover:scale-125 transition-transform duration-500">
                        <i class="fas fa-dot-circle text-custom-blue-500"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-6 text-navy-900">.{!! $package['extension'] !!}</h3>
                    <p class="text-navy-600 mb-8 leading-relaxed text-lg">{{ $package['description'] }}</p>
                    <div class="mb-10">
                        <p class="text-5xl font-black text-navy-900 mb-2">{{ $package['price_formatted'] }}</p>
                        <p class="text-navy-500 text-lg font-medium">/year</p>
                    </div>
                    <a href="{{ route('domains.pricing') }}"
                        class="inline-flex items-center bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 px-8 py-4 rounded-2xl font-bold text-lg hover:from-custom-blue-700 hover:to-navy-800 transition-all duration-300 hover:scale-110 shadow-xl hover:shadow-glow">
                        <i class="fas fa-shopping-cart mr-3"></i>Choose This Plan
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-32 bg-bone-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-custom-blue-500 to-navy-600 rounded-full mb-8">
                    <i class="fas fa-award text-bone-50 text-3xl"></i>
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-navy-900 mb-6">Why Choose Us</h2>
                <p class="text-2xl text-navy-600 max-w-3xl mx-auto font-light">We provide everything you need to establish a strong online presence</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-7xl mx-auto">
                @foreach($whyChooseUs as $item)
                <div class="group text-center p-10 rounded-3xl bg-gradient-to-br from-bone-50 to-custom-blue-50 hover:from-custom-blue-50 hover:to-custom-yellow-50 transition-all duration-700 hover:-translate-y-3 hover:shadow-2xl border-2 border-navy-100 hover:border-custom-blue-300">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-custom-blue-500 to-navy-600 rounded-full mb-8 group-hover:scale-125 transition-transform duration-500 shadow-xl">
                        <i class="{{ $item['icon'] }} text-bone-50 text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-black mb-6 text-navy-900 group-hover:text-custom-blue-600 transition-colors duration-300">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-navy-600 leading-relaxed text-lg">{{ $item['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-32 bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-5xl md:text-6xl font-black mb-8 bg-gradient-to-r from-bone-50 via-custom-blue-200 to-custom-yellow-300 bg-clip-text text-transparent">Ready to Get Started?</h2>
                <p class="text-2xl mb-16 text-custom-blue-200 font-light">Join thousands of satisfied customers who trust us with their online presence</p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('domains.search') }}"
                        class="inline-flex items-center bg-gradient-to-r from-bone-50 to-custom-blue-100 text-navy-900 px-10 py-6 rounded-2xl font-black text-xl hover:from-custom-blue-100 hover:to-custom-blue-200 transition-all duration-300 hover:scale-110 shadow-2xl">
                        <i class="fas fa-search mr-4 text-2xl"></i>Find Your Domain
                    </a>
                    <a href="{{ route('templates.index') }}"
                        class="inline-flex items-center bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 px-10 py-6 rounded-2xl font-black text-xl hover:from-custom-yellow-500 hover:to-custom-yellow-600 transition-all duration-300 hover:scale-110 shadow-2xl hover:shadow-yellow-glow">
                        <i class="fas fa-palette mr-4 text-2xl"></i>Browse Templates
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Custom Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }

    /* Custom gradient animation */
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 15s ease infinite;
    }
</style>
@endsection
