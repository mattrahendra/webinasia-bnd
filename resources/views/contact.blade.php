@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
    <div class="max-w-4xl mx-auto py-16">
        <h1 class="text-3xl font-bold mb-6">Contact Us</h1>
        <p class="text-gray-600 mb-4">Have questions? Reach out to us!</p>
        <form action="#" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="p-2 border rounded w-full" required>
            </div>
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="p-2 border rounded w-full" required>
            </div>
            <div>
                <label for="message" class="block text-gray-700">Message</label>
                <textarea name="message" id="message" class="p-2 border rounded w-full" rows="5" required></textarea>
            </div>
            @include('components.button', ['label' => 'Send Message', 'icon' => 'fas fa-paper-plane'])
        </form>
    </div>
@endsection
