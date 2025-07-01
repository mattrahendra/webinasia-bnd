@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="max-w-md mx-auto py-16">
        <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>
        <form action="{{ route('login') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="p-2 border rounded w-full" required autofocus>
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
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-gray-700">Remember Me</label>
            </div>
            @include('components.button', ['label' => 'Login', 'icon' => 'fas fa-sign-in-alt', 'type' => 'submit'])
            <p class="text-center text-gray-600 mt-4">
                Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
            </p>
        </form>
    </div>
@endsection
