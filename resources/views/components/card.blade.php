<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold">{{ $title }}</h3>
    <p class="text-gray-600 mt-2">{{ $description }}</p>
    @if (isset($price))
        <p class="text-blue-600 font-bold mt-2">{{ $price }}</p>
    @endif
    <div class="mt-4">
        <a href="{{ $link }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-arrow-right mr-1"></i> View Details</a>
    </div>
</div>
