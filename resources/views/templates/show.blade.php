@extends('layouts.app')

@section('title', $template->name)

@section('content')
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">{{ $template->name }}</h1>
        <p class="text-gray-600 mb-4">{{ $template->description ?? 'No description available' }}</p>
        <p class="text-blue-600 font-bold mb-4">{{ $template->price ? '$' . number_format($template->price, 2) : 'Free' }}</p>

        @if ($template->images && $template->images->isNotEmpty())
            <h2 class="text-xl font-semibold mb-4">Images</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($template->images as $image)
                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $template->name }}" class="w-full h-48 object-cover rounded">
                @endforeach
            </div>
        @endif

        @auth
            <form action="{{ route('orders.purchase') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="template_id" value="{{ $template->id }}">
                <div class="mb-4">
                    <label for="domain_name" class="block text-gray-700">Domain Name</label>
                    <input type="text" name="domain_name" id="domain_name" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="domain_extension" class="block text-gray-700">Domain Extension</label>
                    <select name="domain_extension" id="domain_extension" class="p-2 border rounded w-full" required>
                        @foreach (['com', 'net', 'org', 'id', 'co.id', 'web.id'] as $ext)
                            <option value="{{ $ext }}">{{ $ext }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="customer_data[name]" class="block text-gray-700">Name</label>
                    <input type="text" name="customer_data[name]" id="customer_data[name]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[email]" class="block text-gray-700">Email</label>
                    <input type="email" name="customer_data[email]" id="customer_data[email]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[phone]" class="block text-gray-700">Phone</label>
                    <input type="text" name="customer_data[phone]" id="customer_data[phone]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[address]" class="block text-gray-700">Address</label>
                    <input type="text" name="customer_data[address]" id="customer_data[address]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[city]" class="block text-gray-700">City</label>
                    <input type="text" name="customer_data[city]" id="customer_data[city]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[state]" class="block text-gray-700">State</label>
                    <input type="text" name="customer_data[state]" id="customer_data[state]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[postal_code]" class="block text-gray-700">Postal Code</label>
                    <input type="text" name="customer_data[postal_code]" id="customer_data[postal_code]" class="p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="customer_data[country]" class="block text-gray-700">Country (2-letter code)</label>
                    <input type="text" name="customer_data[country]" id="customer_data[country]" class="p-2 border rounded w-full" required>
                </div>
                @include('components.button', ['label' => 'Purchase', 'icon' => 'fas fa-shopping-cart'])
            </form>
        @else
            <p class="text-red-600">Please <a href="{{ route('login') }}" class="underline">login</a> to purchase this template.</p>
        @endauth
    </div>
@endsection
