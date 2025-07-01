<footer class="bg-gray-800 text-white py-6">
    <div class="container mx-auto px-4 text-center">
        <p>&copy; {{ date('Y') }} My Website. All rights reserved.</p>
        <div class="mt-2 space-x-4">
            <a href="{{ route('about') }}" class="hover:text-blue-400">About</a>
            <a href="{{ route('contact') }}" class="hover:text-blue-400">Contact</a>
            <a href="/privacy" class="hover:text-blue-400">Privacy Policy</a>
        </div>
    </div>
</footer>
