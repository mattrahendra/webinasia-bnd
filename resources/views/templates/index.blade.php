@extends('layouts.app')

@section('title', 'Browse Templates')

@section('content')
<div class="min-h-screen">

    <!-- Category Tabs & Content Section -->
    <section class="py-16 bg-bone-50">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Category Navigation -->
            <div class="mb-16">
                <div class="border-b border-navy-200">
                    <nav class="-mb-px flex space-x-8 overflow-x-auto justify-center">
                        <!-- All Templates Tab -->
                        <a href="{{ route('templates.index', array_merge(request()->except('category_id'), ['category_id' => 'all'])) }}"
                            class="group py-6 px-8 border-b-4 font-bold text-lg whitespace-nowrap {{ (!$selectedCategoryId || $selectedCategoryId === 'all') ? 'border-custom-blue-500 text-custom-blue-600' : 'border-transparent text-navy-500 hover:text-custom-blue-600 hover:border-custom-blue-400' }} transition-all duration-300">
                            <i class="fas fa-th-large mr-3 group-hover:scale-125 transition-transform duration-300"></i>
                            All Templates
                            <span class="ml-3 bg-gradient-to-r from-custom-blue-100 to-custom-yellow-100 text-navy-800 py-2 px-4 rounded-full text-sm font-bold">
                                {{ \App\Models\Template::count() }}
                            </span>
                        </a>

                        <!-- Category Tabs -->
                        @foreach($categories as $category)
                        <a href="{{ route('templates.index', array_merge(request()->except('category_id'), ['category_id' => $category->id])) }}"
                            class="group py-6 px-8 border-b-4 font-bold text-lg whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'border-custom-blue-500 text-custom-blue-600' : 'border-transparent text-navy-500 hover:text-custom-blue-600 hover:border-custom-blue-400' }} transition-all duration-300">
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

            <!-- Selected Category Info -->
            @if($selectedCategory)
            <div class="mb-12 p-8 bg-gradient-to-r from-custom-blue-50 to-custom-yellow-50 rounded-3xl border-2 border-custom-blue-200 shadow-xl">
                <div class="flex items-center justify-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-blue-500 to-navy-600 rounded-full">
                        <i class="fas fa-folder text-bone-50 text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-black text-center text-navy-900 mb-4">{{ $selectedCategory->name }}</h2>
                @if($selectedCategory->description)
                <p class="text-navy-700 text-center text-lg leading-relaxed max-w-3xl mx-auto">{{ $selectedCategory->description }}</p>
                @endif
            </div>
            @endif

            <!-- Templates Grid -->
            @if($templates->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 mb-16">
                @foreach($templates as $template)
                <div class="group bg-bone-50 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-700 overflow-hidden border-2 border-navy-100 hover:border-custom-blue-300 hover:-translate-y-3 hover:scale-105">
                    <!-- Template Image -->
                    <div class="relative h-56 bg-gradient-to-br from-navy-100 to-custom-blue-100 overflow-hidden">
                        @if($template->images && $template->images->count() > 0)
                        <img src="{{ Storage::url($template->images->first()->image_path) }}"
                            alt="{{ $template->name }}"
                            class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-custom-blue-100 to-custom-yellow-100">
                            <i class="fas fa-image text-5xl text-custom-blue-400 group-hover:scale-125 transition-transform duration-500"></i>
                        </div>
                        @endif

                        <!-- Category Badge -->
                        @if($template->category)
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center bg-bone-50/95 backdrop-blur-sm text-navy-700 text-xs font-bold px-3 py-2 rounded-full border-2 border-custom-blue-200 shadow-lg">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $template->category->name }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Template Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-navy-900 mb-3 line-clamp-2 group-hover:text-custom-blue-600 transition-colors duration-300">
                            {{ $template->name }}
                        </h3>

                        <p class="text-navy-600 text-sm mb-6 line-clamp-3 leading-relaxed">
                            {{ $template->description ?? 'No description available' }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            @if($template->projects && $template->projects->where('preview_path', '!=', null)->count() > 0)
                            <a href="{{ route('templates.preview', $template) }}"
                                class="flex-1 bg-gradient-to-r from-navy-100 to-custom-blue-100 text-navy-700 px-4 py-3 rounded-xl text-sm font-bold hover:from-navy-200 hover:to-custom-blue-200 transition-all duration-300 text-center group/btn hover:scale-110 shadow-lg"
                                target="_blank">
                                <i class="fas fa-eye mr-2 group-hover/btn:scale-125 transition-transform duration-300"></i>Preview
                            </a>
                            @else
                            <a href="{{ route('templates.show', $template) }}"
                                class="flex-1 bg-gradient-to-r from-navy-100 to-custom-blue-100 text-navy-700 px-4 py-3 rounded-xl text-sm font-bold hover:from-navy-200 hover:to-custom-blue-200 transition-all duration-300 text-center group/btn hover:scale-110 shadow-lg">
                                <i class="fas fa-info-circle mr-2 group-hover/btn:scale-125 transition-transform duration-300"></i>Details
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                <div class="bg-bone-50 rounded-2xl shadow-xl border-2 border-navy-100 p-4">
                    {{ $templates->appends(request()->query())->links() }}
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <div class="max-w-2xl mx-auto">
                    <!-- Empty State Icon -->
                    <div class="inline-flex items-center justify-center w-32 h-32 bg-gradient-to-r from-navy-100 to-custom-blue-100 rounded-full mb-8">
                        <i class="fas fa-search text-6xl text-custom-blue-400"></i>
                    </div>

                    <h3 class="text-4xl font-black text-navy-900 mb-6">No Templates Found</h3>
                    <p class="text-navy-600 text-xl mb-12 leading-relaxed">
                        @if(request('search'))
                        We couldn't find any templates matching "<span class="font-bold text-custom-blue-600">{{ request('search') }}</span>".
                        @else
                        No templates available in this category yet.
                        @endif
                    </p>

                    @if(request('search') || $selectedCategoryId)
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <a href="{{ route('templates.index') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 rounded-2xl hover:from-custom-blue-700 hover:to-navy-800 font-bold text-lg transition-all duration-300 hover:scale-110 shadow-xl">
                            <i class="fas fa-arrow-left mr-3"></i>View All Templates
                        </a>

                        @if(request('search'))
                        <a href="{{ route('templates.index', ['category_id' => $selectedCategoryId]) }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 rounded-2xl hover:from-custom-yellow-500 hover:to-custom-yellow-600 font-bold text-lg transition-all duration-300 hover:scale-110 shadow-xl">
                            <i class="fas fa-times mr-3"></i>Clear Search
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-black mb-6 bg-gradient-to-r from-bone-50 via-custom-blue-200 to-custom-yellow-300 bg-clip-text text-transparent">
                    Need Something Custom?
                </h2>
                <p class="text-xl mb-12 text-custom-blue-200 font-light">
                    Can't find the perfect template? Our team can create a custom design tailored to your specific needs.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('domains.search') }}"
                        class="inline-flex items-center bg-gradient-to-r from-bone-50 to-custom-blue-100 text-navy-900 px-8 py-4 rounded-2xl font-bold text-lg hover:from-custom-blue-100 hover:to-custom-blue-200 transition-all duration-300 hover:scale-110 shadow-2xl">
                        <i class="fas fa-search mr-3"></i>Find Your Domain
                    </a>
                    <a href="mailto:support@yourdomain.com"
                        class="inline-flex items-center bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 px-8 py-4 rounded-2xl font-bold text-lg hover:from-custom-yellow-500 hover:to-custom-yellow-600 transition-all duration-300 hover:scale-110 shadow-2xl hover:shadow-yellow-glow">
                        <i class="fas fa-envelope mr-3"></i>Request Custom Design
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

    /* Custom shadows */
    .shadow-glow {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }

    .shadow-yellow-glow {
        box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
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

    /* Pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination .page-link {
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #1e3a8a;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 2px solid #cbd5e1;
        text-decoration: none;
    }

    .pagination .page-link:hover {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: #fefce8;
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3b82f6, #1e40af);
        color: #fefce8;
        border-color: #3b82f6;
    }
</style>
@endsection
