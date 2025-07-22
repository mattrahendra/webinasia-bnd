@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100">
            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Payment Pending</h1>
        <p class="mt-2 text-lg text-gray-600">Your payment is being processed.</p>
        <p class="mt-1 text-gray-500">This may take a few minutes. We'll notify you once it's completed.</p>

        <div class="mt-8 space-x-4">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Check Order Status
            </a>
        </div>
    </div>
</div>
@endsection
