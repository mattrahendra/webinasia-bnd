<button type="{{ $type ?? 'button' }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 {{ $class ?? '' }}">
    <i class="{{ $icon ?? 'fas fa-check' }} mr-1"></i> {{ $label }}
</button>
