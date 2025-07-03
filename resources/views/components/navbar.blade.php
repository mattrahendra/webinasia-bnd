<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="text-2xl font-bold">
            <a href="{{ route('home') }}"><img src="{{ asset('images/webinasia.svg') }}" alt="webinasia logo" class="h-10 inline"></a>
        </div>

        <!-- Links -->
        <div class="ml-7 space-x-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-blue-600">Template</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600">About</a>
            <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-600">Contact</a>
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
