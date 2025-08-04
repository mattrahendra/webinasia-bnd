<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - My Website</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="..." crossorigin="anonymous" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'navy': {
                            50: '#f0f4ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b'
                        },
                        'bone': {
                            50: '#fefefe',
                            100: '#fefefe',
                            200: '#fefefe',
                            300: '#fdfdfd',
                            400: '#fcfcfc',
                            500: '#faf9f7',
                            600: '#e8e6e0',
                            700: '#d6d3ca',
                            800: '#c4c0b4',
                            900: '#b2ad9e'
                        },
                        'custom-blue': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        'custom-yellow': {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f'
                        }
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    backdropBlur: {
                        xs: '2px',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Alert animations */
        .alert {
            animation: slideInFromTop 0.5s ease-out;
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Background patterns */
        .bg-pattern {
            background-image:
                radial-gradient(circle at 25px 25px, rgba(59, 130, 246, 0.1) 2px, transparent 0),
                radial-gradient(circle at 75px 75px, rgba(251, 191, 36, 0.1) 2px, transparent 0);
            background-size: 100px 100px;
        }

        .bg-dots {
            background-image: radial-gradient(circle, rgba(59, 130, 246, 0.15) 1px, transparent 1px);
            background-size: 20px 20px;
        }

        /* Gradient animations */
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 8s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Enhanced shadows */
        .shadow-glow {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .shadow-yellow-glow {
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
        }

        /* Hover effects */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-bone-50 via-custom-blue-50 to-navy-50 text-navy-900">
    <!-- Background Pattern -->
    <div class="fixed inset-0 bg-dots opacity-30 pointer-events-none"></div>

    <!-- Navigation -->
    @include('components.navbar')

    <!-- Main Content -->
    <main class="relative">
        <!-- Alert Messages -->
        @if (session('success'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 alert">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl shadow-2xl border border-green-400/20 backdrop-blur-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-xl mr-3"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 alert">
                <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-8 py-4 rounded-2xl shadow-2xl border border-red-400/20 backdrop-blur-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-white/80 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Content Area -->
        <div class="relative z-10">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="fixed bottom-8 right-8 bg-gradient-to-r from-custom-blue-600 to-navy-700 text-white w-14 h-14 rounded-full shadow-2xl hover:shadow-glow transform hover:scale-110 transition-all duration-300 opacity-0 pointer-events-none z-50">
        <i class="fas fa-arrow-up text-lg"></i>
    </button>

    <!-- Loading Overlay (optional) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-navy-900/80 backdrop-blur-sm z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <div class="flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-custom-blue-600"></div>
                <span class="text-navy-800 font-semibold">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Scroll to top functionality
        window.addEventListener('scroll', function() {
            const scrollToTopBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollToTopBtn.classList.add('opacity-100');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                scrollToTopBtn.classList.remove('opacity-100');
            }
        });

        document.getElementById('scrollToTop').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus effects to input fields
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(function(input) {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-custom-blue-300');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-custom-blue-300');
                });
            });
        });

        // Smooth page transitions
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in effect to page content
            document.body.style.opacity = '0';
            setTimeout(function() {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 50);
        });

        // Enhanced mobile menu functionality (if needed)
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');

            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                if (menuIcon && closeIcon) {
                    menuIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                }
            }
        }

        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close mobile menu
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    toggleMobileMenu();
                }

                // Close modals
                const modals = document.querySelectorAll('.modal');
                modals.forEach(function(modal) {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });

        // Performance optimization: Lazy load images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
