@extends('layouts.app')

@section('title', 'Browse Templates')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Templates</h1>

        <form action="{{ route('templates.index') }}" method="GET" class="mb-6">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <input type="text"
                        name="search"
                        placeholder="Search templates..."
                        value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </form>
    </div>

    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto">
                <a href="{{ route('templates.index', array_merge(request()->except('category_id'), ['category_id' => 'all'])) }}"
                    class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ (!$selectedCategoryId || $selectedCategoryId === 'all') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Templates
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">
                        {{ \App\Models\Template::count() }}
                    </span>
                </a>

                @foreach($categories as $category)
                <a href="{{ route('templates.index', array_merge(request()->except('category_id'), ['category_id' => $category->id])) }}"
                    class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $selectedCategoryId == $category->id ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $category->name }}
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs">
                        {{ $category->templates_count }}
                    </span>
                </a>
                @endforeach
            </nav>
        </div>
    </div>

    @if($selectedCategory)
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <h2 class="text-lg font-semibold text-blue-900 mb-2">{{ $selectedCategory->name }}</h2>
        @if($selectedCategory->description)
        <p class="text-blue-700">{{ $selectedCategory->description }}</p>
        @endif
    </div>
    @endif

    @if($templates->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @foreach($templates as $template)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div class="h-48 bg-gray-200 overflow-hidden">
                @if($template->images && $template->images->count() > 0)
                <img src="{{ Storage::url($template->images->first()->image_path) }}"
                    alt="{{ $template->name }}"
                    class="w-full h-full object-cover">
                @else
                <div class=" w-full h-full flex items-center justify-center bg-gray-100">
                    <i class="fas fa-image text-4xl text-gray-400"></i>
                </div>
                @endif
            </div>

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

                    <form action="{{ route('orders.create') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="template_id" value="{{ $template->id }}">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                            <i class="fas fa-shopping-cart mr-1"></i>Lanjut ke Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex justify-center">
        {{ $templates->appends(request()->query())->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <div class="max-w-md mx-auto">
            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No templates found</h3>
            <p class="text-gray-500 mb-4">
                @if(request('search'))
                No templates match your search criteria "{{ request('search') }}".
                @else
                No templates available in this category.
                @endif
            </p>
            @if(request('search') || $selectedCategoryId)
            <a href="{{ route('templates.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>View All Templates
            </a>
            @endif
        </div>
    </div>
    @endif
</div>

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
