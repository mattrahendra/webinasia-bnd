@extends('layouts.app')

@section('title', 'Preview Template: ' . $template->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h4 mb-1">Preview: {{ $template->name }}</h1>
                    <p class="text-muted mb-0">{{ $template->description }}</p>
                </div>
                <div>
                    <a href="{{ route('templates.show', $template) }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('templates.select', $template) }}" class="btn btn-primary">
                        <i class="fas fa-check"></i> Pilih Template
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Preview Template</h5>
                    <div>
                        <a href="{{ $previewUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="preview-container" style="height: 80vh; position: relative;">
                        <iframe
                            src="{{ $previewUrl }}"
                            width="100%"
                            height="100%"
                            frameborder="0"
                            style="border: none;"
                            onload="this.style.opacity=1"
                            style="opacity: 0; transition: opacity 0.3s;">
                        </iframe>
                        <div id="loading" class="d-flex justify-content-center align-items-center position-absolute w-100 h-100" style="top: 0; left: 0; background: rgba(255,255,255,0.9);">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iframe = document.querySelector('iframe');
    const loading = document.getElementById('loading');

    iframe.onload = function() {
        loading.style.display = 'none';
        this.style.opacity = '1';
    };

    // Hide loading after 5 seconds as fallback
    setTimeout(function() {
        loading.style.display = 'none';
        iframe.style.opacity = '1';
    }, 5000);
});
</script>
@endpush
@endsection
