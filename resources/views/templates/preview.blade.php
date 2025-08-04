@extends('layouts.app')

@section('title', 'Preview Template: ' . $template->name)

@section('content')
<div class="min-h-screen bg-bone-50">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-navy-950 via-navy-900 to-custom-blue-900 text-bone-50 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <!-- Template Info -->
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-custom-yellow-400 to-custom-yellow-500 rounded-full mr-4">
                            <i class="fas fa-eye text-navy-900 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-custom-blue-200 text-sm font-medium mb-1">Template Preview</p>
                            <h1 class="text-2xl md:text-3xl font-black text-bone-50">{{ $template->name }}</h1>
                        </div>
                    </div>
                    @if($template->description)
                    <p class="text-custom-blue-200 text-lg leading-relaxed max-w-2xl">{{ $template->description }}</p>
                    @endif

                    @if($template->category)
                    <div class="mt-4">
                        <span class="inline-flex items-center bg-bone-50/10 backdrop-blur-sm text-custom-blue-200 text-sm font-bold px-4 py-2 rounded-full border border-custom-blue-400/30">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $template->category->name }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 lg:flex-shrink-0">
                    <a href="{{ route('templates.show', $template) }}"
                        class="inline-flex items-center justify-center px-6 py-4 bg-bone-50/10 backdrop-blur-sm text-bone-50 rounded-2xl hover:bg-bone-50/20 font-bold text-lg transition-all duration-300 hover:scale-105 border border-bone-50/20 hover:border-bone-50/40">
                        <i class="fas fa-arrow-left mr-3"></i>Back to Details
                    </a>

                    <a href="{{ $previewUrl }}" target="_blank"
                        class="inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-custom-blue-100 to-bone-50 text-navy-900 rounded-2xl hover:from-bone-50 hover:to-custom-blue-100 font-bold text-lg transition-all duration-300 hover:scale-105 shadow-xl">
                        <i class="fas fa-external-link-alt mr-3"></i>Open in New Tab
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Preview Section -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Preview Controls -->
            <div class="mb-6">
                <div class="bg-bone-50 rounded-2xl shadow-xl border-2 border-navy-100 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-6">
                            <h2 class="text-xl font-bold text-navy-900">Live Preview</h2>

                            <!-- Device Size Selector -->
                            <div class="flex bg-navy-100 rounded-xl p-1" id="deviceSelector">
                                <button class="device-btn active px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300" data-width="100%" data-label="Desktop">
                                    <i class="fas fa-desktop mr-2"></i>Desktop
                                </button>
                                <button class="device-btn px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300" data-width="768px" data-label="Tablet">
                                    <i class="fas fa-tablet-alt mr-2"></i>Tablet
                                </button>
                                <button class="device-btn px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300" data-width="375px" data-label="Mobile">
                                    <i class="fas fa-mobile-alt mr-2"></i>Mobile
                                </button>
                            </div>
                        </div>

                        <!-- Preview Actions -->
                        <div class="flex items-center space-x-4">
                            <button id="refreshPreview" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-custom-blue-100 to-custom-blue-200 text-navy-700 rounded-xl hover:from-custom-blue-200 hover:to-custom-blue-300 font-bold text-sm transition-all duration-300 hover:scale-105">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>

                            <div class="text-sm text-navy-600 bg-navy-100 px-4 py-2 rounded-xl">
                                <span id="currentDevice">Desktop View</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Container -->
            <div class="relative bg-bone-50 rounded-3xl shadow-2xl border-2 border-navy-100 overflow-hidden">
                <!-- Preview Frame -->
                <div class="relative" style="min-height: 80vh;">
                    <!-- Loading State -->
                    <div id="previewLoading" class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-bone-50 to-custom-blue-50 z-10">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-custom-blue-500 to-navy-600 rounded-full mb-6 animate-pulse">
                                <i class="fas fa-spinner fa-spin text-bone-50 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-navy-900 mb-2">Loading Preview</h3>
                            <p class="text-navy-600">Please wait while we load the template...</p>
                        </div>
                    </div>

                    <!-- Preview Iframe Container -->
                    <div id="previewContainer" class="transition-all duration-500 ease-in-out mx-auto" style="width: 100%; opacity: 0;">
                        <div class="bg-navy-100 rounded-t-2xl px-6 py-3 border-b border-navy-200">
                            <div class="flex items-center space-x-3">
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                                <div class="flex-1 bg-bone-50 rounded-lg px-4 py-2">
                                    <p class="text-sm text-navy-600 truncate">{{ $previewUrl }}</p>
                                </div>
                            </div>
                        </div>

                        <iframe
                            id="previewFrame"
                            src="{{ $previewUrl }}"
                            width="100%"
                            height="800"
                            frameborder="0"
                            class="block bg-bone-50"
                            onload="handleIframeLoad()">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- Template Actions -->
            <div class="mt-8 text-center">
                <div class="inline-flex flex-col sm:flex-row gap-4 p-6 bg-gradient-to-r from-navy-50 to-custom-blue-50 rounded-3xl border-2 border-navy-100">
                    <div class="text-center sm:text-left flex-1">
                        <h3 class="text-xl font-bold text-navy-900 mb-2">Ready to get started?</h3>
                        <p class="text-navy-600">This template is perfect for your project. Order now to customize it with your content.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                        <a href="{{ route('templates.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-bone-50 text-navy-700 rounded-xl hover:bg-navy-100 font-bold transition-all duration-300 hover:scale-105 border-2 border-navy-200">
                            <i class="fas fa-search mr-2"></i>Browse More
                        </a>

                        <form action="{{ route('orders.create') }}" method="POST" class="inline-block">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $template->id }}">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-custom-blue-600 to-navy-700 text-bone-50 rounded-xl hover:from-custom-blue-700 hover:to-navy-800 font-bold transition-all duration-300 hover:scale-105 shadow-xl hover:shadow-glow">
                                <i class="fas fa-rocket mr-2"></i>Order Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
// Global variables
let currentWidth = '100%';
let currentLabel = 'Desktop';

// DOM elements
const previewContainer = document.getElementById('previewContainer');
const previewFrame = document.getElementById('previewFrame');
const previewLoading = document.getElementById('previewLoading');
const currentDeviceSpan = document.getElementById('currentDevice');
const deviceButtons = document.querySelectorAll('.device-btn');
const refreshButton = document.getElementById('refreshPreview');

// Handle iframe load
function handleIframeLoad() {
    setTimeout(() => {
        previewLoading.style.opacity = '0';
        setTimeout(() => {
            previewLoading.style.display = 'none';
            previewContainer.style.opacity = '1';
        }, 300);
    }, 1000);
}

// Device selector functionality
deviceButtons.forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        deviceButtons.forEach(btn => {
            btn.classList.remove('active');
            btn.classList.remove('bg-gradient-to-r', 'from-custom-blue-500', 'to-navy-600', 'text-bone-50');
            btn.classList.add('text-navy-600', 'hover:text-navy-800');
        });

        // Add active class to clicked button
        this.classList.add('active');
        this.classList.remove('text-navy-600', 'hover:text-navy-800');
        this.classList.add('bg-gradient-to-r', 'from-custom-blue-500', 'to-navy-600', 'text-bone-50');

        // Update preview size
        currentWidth = this.dataset.width;
        currentLabel = this.dataset.label;

        previewContainer.style.width = currentWidth;
        currentDeviceSpan.textContent = currentLabel + ' View';

        // Add transition effect
        previewContainer.style.transform = 'scale(0.95)';
        setTimeout(() => {
            previewContainer.style.transform = 'scale(1)';
        }, 150);
    });
});

// Refresh preview functionality
refreshButton.addEventListener('click', function() {
    this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Refreshing...';
    this.disabled = true;

    // Show loading
    previewLoading.style.display = 'flex';
    previewLoading.style.opacity = '1';
    previewContainer.style.opacity = '0';

    // Reload iframe
    previewFrame.src = previewFrame.src;

    // Reset button after 2 seconds
    setTimeout(() => {
        this.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Refresh';
        this.disabled = false;
    }, 2000);
});

// Fallback loading hide
setTimeout(() => {
    if (previewLoading.style.display !== 'none') {
        handleIframeLoad();
    }
}, 8000);

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active state
    deviceButtons[0].classList.add('active', 'bg-gradient-to-r', 'from-custom-blue-500', 'to-navy-600', 'text-bone-50');
    currentDeviceSpan.textContent = 'Desktop View';
});
</script>
@endpush

<!-- Custom Styles -->
<style>
    /* Custom shadows */
    .shadow-glow {
        box-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }

    .shadow-yellow-glow {
        box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
    }

    /* Smooth transitions */
    #previewContainer {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #previewLoading {
        transition: opacity 0.3s ease-in-out;
    }

    /* Device button hover effects */
    .device-btn:hover:not(.active) {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        transform: translateY(-1px);
    }

    .device-btn.active {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
    }

    /* Responsive iframe */
    @media (max-width: 768px) {
        #previewFrame {
            height: 600px;
        }

        #previewContainer {
            width: 100% !important;
        }
    }

    /* Custom scrollbar for iframe area */
    .preview-container::-webkit-scrollbar {
        width: 8px;
    }

    .preview-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .preview-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .preview-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
