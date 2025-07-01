@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Browse Categories</h1>
        <form action="{{ route('categories.index') }}" method="GET" class="mb-6">
            <input type="text" name="search" placeholder="Search categories..." class="p-2 border rounded w-full md:w-1/3">
            <label class="inline-flex items-center mt-2">
                <input type="checkbox" name="with_templates" value="1" {{ request('with_templates') ? 'checked' : '' }} class="mr-2">
                Include Templates
            </label>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($categories as $category)
                @include('components.card', [
                    'title' => $category->name,
                    'description' => $category->description ?? 'No description available',
                    'link' => route('categories.show', $category)
                ])
            @endforeach
        </div>
        {{ $categories->links() }}
    </div>
@endsection
