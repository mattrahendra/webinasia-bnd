<nav class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="text-2xl font-bold">
            <a href="{{ route('home') }}"><img src="{{ asset('images/webinasia.svg') }}" alt="webinasia logo" class="h-10 inline"></a>
        </div>

        <!-- Links -->
        <div class="space-x-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="{{ route('domains.index') }}" class="text-gray-600 hover:text-blue-600">Domain</a>
            <a href="{{ route('templates.index') }}" class="text-gray-600 hover:text-blue-600">Template</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-blue-600">About</a>
            <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-600">Contact</a>
        </div>

        <!-- Auth Links -->
        <div class="space-x-4">
            @auth
                <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-blue-600"><i class="fas fa-user mr-1"></i> My Orders</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-blue-600"><i class="fas fa-sign-out-alt mr-1"></i> Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"><i class="fas fa-user-plus mr-1"></i> Register</a>
            @endauth
        </div>
    </div>
</nav>
