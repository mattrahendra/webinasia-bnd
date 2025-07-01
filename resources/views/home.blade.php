@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="text-center py-16">
        <h1 class="text-4xl font-bold mb-6">Welcome to Our Website</h1>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">Discover premium website templates and domain names to build your online presence. Browse our categories, check domain availability, and start your journey today!</p>
        <div class="space-x-4">
            <a href="{{ route('templates.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700"><i class="fas fa-desktop mr-2"></i> Browse Templates</a>
            <a href="{{ route('domains.search') }}" class="bg-gray-600 text-white px-6 py-3 rounded hover:bg-gray-700"><i class="fas fa-globe mr-2"></i> Search Domains</a>
        </div>
    </div>
@endsection
