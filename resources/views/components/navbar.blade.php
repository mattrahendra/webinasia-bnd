<nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-6 py-3 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('home') }}" class="transition-transform hover:scale-105">
                <img src="{{ asset('images/webinasia.svg') }}" alt="webinasia logo" class="h-8 w-auto">
            </a>
        </div>

        <!-- Links -->
        <div class="flex items-center space-x-8">
            <a href="{{ route('home') }}"
               class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200 relative group">
                Home
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-900 transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('templates.index') }}"
               class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200 relative group">
                Template
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-900 transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('about') }}"
               class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200 relative group">
                About
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-900 transition-all duration-200 group-hover:w-full"></span>
            </a>
            <a href="{{ route('contact') }}"
               class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200 relative group">
                Contact
                <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-900 transition-all duration-200 group-hover:w-full"></span>
            </a>
        </div>
    </div>
</nav>

<script>
    // Check localStorage for saved items and update the badge
    document.addEventListener('DOMContentLoaded', function() {
        const savedItems = JSON.parse(localStorage.getItem('orderItems')) || [];
        const badge = document.querySelector('.fa-shopping-cart + span');
        if (badge) {
            badge.textContent = savedItems.length > 0 ? savedItems.length : '';
            badge.classList.toggle('hidden', savedItems.length === 0);
        }
    });
</script>
