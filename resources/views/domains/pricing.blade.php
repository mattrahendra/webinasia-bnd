@extends('layouts.app')

@section('title', 'Domain Pricing')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Domain Pricing</h1>
        <div class="grid gap-4">
            @foreach ($pricing as $ext => $price)
                <div class="bg-white shadow rounded-lg p-4">
                    <p class="text-lg font-semibold">.{{ $ext }}</p>
                    <p class="text-blue-600 font-bold">{{ $price }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
