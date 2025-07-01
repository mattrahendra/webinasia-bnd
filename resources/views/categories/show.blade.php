@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">{{ $category->name }}</h1>
        <p class="text-gray-600 mb-6">{{ $category->description ?? 'No description available' }}</p>

        @if ($category->templates && $category->templates->isNotEmpty())
            <h2 class="text-xl font-semibold mb-4">Templates</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($category->templates as $template)
                    @include('components.card', [
                        'title' => $template->name,
                        'description' => $template->description ?? 'No description available',
                        'price' => $template->price ? '$' . number_format($template->price, 2) : 'Free',
                        'link' => route('templates.show', $template)
                    ])
                @endforeach
            </div>
        @else
            <p>No templates available in this category.</p>
        @endif
    </div>
@endsection
