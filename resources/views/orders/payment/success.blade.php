@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">Payment Successful! 🎉</h1>
        <p class="mt-2 text-lg text-gray-600">Your payment has been processed successfully.</p>
        <p class="mt-1 text-gray-500">You will receive a confirmation email shortly.</p>

        <div class="mt-8">
            <a href="{{ route('orders.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                View Your Orders
            </a>
        </div>
    </div>
</div>
@endsection
