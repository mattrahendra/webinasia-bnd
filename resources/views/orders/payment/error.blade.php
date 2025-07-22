@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Payment Failed</h1>
        <p class="mt-2 text-lg text-gray-600">Unfortunately, your payment could not be processed.</p>
        <p class="mt-1 text-gray-500">Please try again or contact our support team for assistance.</p>

        <div class="mt-8 space-x-4">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                View Orders
            </a>
            <a href="{{ route('home') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Try Again
            </a>
        </div>
    </div>
</div>
@endsection
