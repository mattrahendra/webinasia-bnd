@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="max-w-md mx-auto py-16">
        <h1 class="text-3xl font-bold mb-6 text-center">Register</h1>
        <form action="{{ route('register') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="p-2 border rounded w-full" required autofocus>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="p-2 border rounded w-full" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="p-2 border rounded w-full" required>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="p-2 border rounded w-full" required>
            </div>
            @include('components.button', ['label' => 'Register', 'icon' => 'fas fa-user-plus', 'type' => 'submit'])
            <p class="text-center text-gray-600 mt-4">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            </p>
        </form>
    </div>
@endsection
