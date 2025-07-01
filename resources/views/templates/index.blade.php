@extends('layouts.app')

@section('title', 'Templates')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Browse Templates</h1>
        <form action="{{ route('templates.index') }}" method="GET" class="mb-6 space-y-4">
            <div class="flex flex-col md:flex-row md:space-x-4">
                <input type="text" name="search" placeholder="Search templates..." value="{{ request('search') }}" class="p-2 border rounded w-full md:w-1/3">
                <select name="category_id" class="p-2 border rounded w-full md:w-1/3">
                    <option value="">All Categories</option>
                    @foreach (\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-4">
                <input type="number" name="min_price" placeholder="Min Price" value="{{ request('min_price') }}" class="p-2 border rounded w-full md:w-1/4">
                <input type="number" name="max_price" placeholder="Max Price" value="{{ request('max_price') }}" class="p-2 border rounded w-full md:w-1/4">
            </div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="with_category" value="1" {{ request('with_category') ? 'checked' : '' }} class="mr-2">
                Include Category
            </label>
            <label class="inline-flex items-center ml-4">
                <input type="checkbox" name="with_images" value="1" {{ request('with_images') ? 'checked' : '' }} class="mr-2">
                Include Images
            </label>
            @include('components.button', ['label' => 'Filter', 'icon' => 'fas fa-filter'])
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($templates as $template)
                @include('components.card', [
                    'title' => $template->name,
                    'description' => $template->description ?? 'No description available',
                    'price' => $template->price ? '$' . number_format($template->price, 2) : 'Free',
                    'link' => route('templates.show', $template)
                ])
            @endforeach
        </div>
        {{ $templates->links() }}
    </div>
@endsection
