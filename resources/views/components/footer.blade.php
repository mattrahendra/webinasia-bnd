<footer class="bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23fbbf24" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Main Footer Content -->
        <div class="py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Company Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-8 group">
                        <img src="{{ asset('images/webinasia.svg') }}" alt="webinasia logo" class="h-12 w-auto mr-4 group-hover:rotate-6 transition-transform duration-300">
                        <div>
                            <div class="h-1 w-0 group-hover:w-full bg-gradient-to-r from-custom-blue-400 to-custom-yellow-400 transition-all duration-500 rounded-full mt-1"></div>
                        </div>
                    </div>
                    <p class="text-custom-blue-200 text-lg leading-relaxed mb-8 max-w-md">
                        Your trusted partner for premium domains and professional website templates. We help businesses establish a strong online presence with cutting-edge design and reliable service.
                    </p>

                    <!-- Social Media -->
                    <div class="flex space-x-4">
                        <a href="#" class="group w-12 h-12 bg-custom-blue-800/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-custom-yellow-500 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-facebook-f text-bone-50 group-hover:scale-125 transition-transform duration-300"></i>
                        </a>
                        <a href="#" class="group w-12 h-12 bg-custom-blue-800/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-custom-yellow-500 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-twitter text-bone-50 group-hover:scale-125 transition-transform duration-300"></i>
                        </a>
                        <a href="#" class="group w-12 h-12 bg-custom-blue-800/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-custom-yellow-500 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-instagram text-bone-50 group-hover:scale-125 transition-transform duration-300"></i>
                        </a>
                        <a href="#" class="group w-12 h-12 bg-custom-blue-800/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-custom-yellow-500 transition-all duration-300 hover:scale-110 hover:shadow-lg">
                            <i class="fab fa-linkedin-in text-bone-50 group-hover:scale-125 transition-transform duration-300"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xl font-black text-custom-yellow-400 mb-8 flex items-center">
                        <i class="fas fa-link mr-3"></i>Quick Links
                    </h4>
                    <div class="space-y-4">
                        <a href="{{ route('home') }}" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Home</span>
                        </a>
                        <a href="{{ route('templates.index') }}" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Templates</span>
                        </a>
                        <a href="{{ route('about') }}" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">About</span>
                        </a>
                        <a href="{{ route('contact') }}" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Contact</span>
                        </a>
                    </div>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-xl font-black text-custom-yellow-400 mb-8 flex items-center">
                        <i class="fas fa-headset mr-3"></i>Support
                    </h4>
                    <div class="space-y-4">
                        <a href="/privacy" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Privacy Policy</span>
                        </a>
                        <a href="/terms" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Terms of Service</span>
                        </a>
                        <a href="/faq" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">FAQ</span>
                        </a>
                        <a href="/help" class="group flex items-center text-custom-blue-200 hover:text-bone-50 transition-colors duration-300">
                            <i class="fas fa-chevron-right mr-3 text-custom-yellow-400 group-hover:translate-x-1 transition-transform duration-300"></i>
                            <span class="group-hover:text-custom-yellow-400 transition-colors duration-300">Help Center</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="py-16 border-t border-custom-blue-800/50">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 rounded-full mb-6">
                    <i class="fas fa-envelope text-bone-50 text-2xl"></i>
                </div>
                <h3 class="text-3xl font-black text-bone-50 mb-4">Stay Updated</h3>
                <p class="text-custom-blue-200 text-lg mb-8 max-w-2xl mx-auto">
                    Get the latest updates on new templates, domain offers, and web design trends delivered to your inbox.
                </p>

                <!-- Newsletter Form -->
                <form class="flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                    <div class="flex-1 relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 transform -translate-y-1/2 text-navy-400"></i>
                        <input type="email" placeholder="Enter your email address..."
                            class="w-full pl-12 pr-4 py-4 rounded-2xl sm:rounded-r-none border-0 focus:ring-4 focus:ring-custom-yellow-400/50 text-navy-800 bg-bone-50 placeholder-navy-400 font-medium text-lg"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 text-navy-900 px-8 py-4 rounded-2xl sm:rounded-l-none font-bold text-lg hover:from-custom-yellow-500 hover:to-custom-yellow-600 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-yellow-glow">
                        <i class="fas fa-paper-plane mr-2"></i>Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="py-8 border-t border-custom-blue-800/50">
            <div class="flex flex-col md:flex-row items-center justify-between text-center md:text-left">
                <div class="flex items-center mb-4 md:mb-0">
                    <p class="text-custom-blue-200 text-base">
                        &copy; {{ date('Y') }} <span class="font-bold text-bone-50">webinasia</span>. All rights reserved.
                    </p>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="flex items-center text-custom-blue-200">
                        <i class="fas fa-heart text-custom-yellow-400 mr-2 animate-pulse"></i>
                        <span class="text-sm">Made with love in Indonesia</span>
                    </div>
                    <div class="flex items-center text-custom-blue-200">
                        <i class="fas fa-shield-alt text-custom-yellow-400 mr-2"></i>
                        <span class="text-sm">SSL Secured</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Back to Top Button -->
    <button id="back-to-top" class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-custom-yellow-500 to-custom-yellow-600 text-bone-50 rounded-full shadow-2xl hover:shadow-yellow-glow transition-all duration-300 hover:scale-110 opacity-0 invisible z-50 group">
        <i class="fas fa-chevron-up text-xl group-hover:scale-125 transition-transform duration-300"></i>
    </button>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');

    // Show/hide back to top button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.add('opacity-0', 'invisible');
            backToTopButton.classList.remove('opacity-100', 'visible');
        }
    });

    // Smooth scroll to top when button is clicked
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Newsletter form submission (you can customize this)
    const newsletterForm = document.querySelector('footer form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;

            // Here you can add your newsletter subscription logic
            alert('Thank you for subscribing! We\'ll keep you updated.');
            this.reset();
        });
    }
});</script>
