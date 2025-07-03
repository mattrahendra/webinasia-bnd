@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Hero Section with Domain Search -->
    <section class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Find Your Perfect Domain</h1>
                <p class="text-xl mb-8">Secure your online presence with our premium domains</p>

                <!-- Domain Search Form -->
                <form action="{{ route('domains.search') }}" method="GET" class="max-w-2xl mx-auto flex">
                    <input type="text" name="domain" placeholder="Enter your domain name..."
                        class="flex-grow p-4 rounded-l-lg border-0 focus:ring-2 focus:ring-blue-300"
                        required>
                    <button type="submit"
                        class="bg-yellow-400 text-gray-900 px-6 py-4 rounded-r-lg hover:bg-yellow-500">
                        Search
                    </button>
                </form>
            </div>

            <!-- Featured Domains -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredDomains as $domain)
                <div class="bg-white text-gray-800 rounded-lg p-6 shadow-md text-center">
                    <h3 class="text-xl font-semibold mb-2">.{!! $domain['extension'] !!}</h3>
                    <p class="text-gray-600 mb-2">{{ $domain['description'] }}</p>
                    <p class="text-green-600 font-bold mb-4">{{ $domain['price_formatted'] }}</p>
                    <a href="{{ route('domains.search') }}?domain=example&extensions[]={{ $domain['extension'] }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Check Availability
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Templates Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Templates</h2>

            <!-- Category Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto justify-center">
                        <!-- All Categories Tab -->
                        <a href="{{ route('templates.index', ['category_id' => 'all']) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            All Templates
                            <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">
                                {{ \App\Models\Template::count() }}
                            </span>
                        </a>

                        <!-- Category Tabs -->
                        @foreach($categories as $category)
                        <a href="{{ route('templates.index', ['category_id' => $category->id]) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            {{ $category->name }}
                            <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">
                                {{ $category->templates_count }}
                            </span>
                        </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Templates Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($featuredTemplates as $template)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Template Image -->
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        @if($template->images && $template->images->count() > 0)
                        <img src="{{ Storage::url($template->images->first()->image_path) }}"
                            alt="{{ $template->name }}"
                            class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                        @endif
                    </div>

                    <!-- Template Info -->
                    <div class="p-4">
                        <div class="mb-2">
                            @if($template->category)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $template->category->name }}
                            </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            {{ $template->name }}
                        </h3>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $template->description ?? 'No description available' }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            @if($template->projects && $template->projects->where('preview_path', '!=', null)->count() > 0)
                            <a href="{{ route('templates.preview', $template) }}"
                                class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors text-center"
                                target="_blank">
                                <i class="fas fa-eye mr-1"></i>Preview
                            </a>
                            @else
                            <a href="{{ route('templates.show', $template) }}"
                                class="flex-1 bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors text-center">
                                <i class="fas fa-info-circle mr-1"></i>Details
                            </a>
                            @endif

                            @auth
                            <form action="{{ route('templates.select', $template) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-check mr-1"></i>Select Template
                                </button>
                            </form>
                            @else
                            <a href="{{ route('login') }}"
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login to Select
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- View All Templates Button -->
            <div class="text-center">
                <a href="{{ route('templates.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-th-large mr-2"></i>View All Templates
                </a>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="bg-gray-200 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Our Pricing Plans</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($pricingPackages as $package)
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <h3 class="text-xl font-semibold mb-4">.{!! $package['extension'] !!}</h3>
                    <p class="text-gray-600 mb-4">{{ $package['description'] }}</p>
                    <p class="text-3xl font-bold mb-4">{{ $package['price_formatted'] }}/year</p>
                    <a href="{{ route('domains.pricing') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Choose Plan
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($whyChooseUs as $item)
                <div class="text-center">
                    <i class="{{ $item['icon'] }} text-4xl text-blue-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">{{ $item['title'] }}</h3>
                    <p class="text-gray-600">{{ $item['description'] }}</p>
                </div>
                @endforeach
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
</style>
@endsection
