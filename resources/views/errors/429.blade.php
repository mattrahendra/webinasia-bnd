@extends('layouts.app')

@section('title', '429 Too Many Requests')

@section('content')
    <div class="text-center py-16">
        <h1 class="text-6xl font-bold text-red-600">429</h1>
        <p class="text-2xl text-gray-600 mt-4">Too Many Requests: Please try again later.</p>
        <a href="{{ route('home') }}" class="mt-6 inline-block text-blue-600 hover:text-blue-800"><i class="fas fa-home mr-1"></i> Back to Home</a>
    </div>
@endsection
