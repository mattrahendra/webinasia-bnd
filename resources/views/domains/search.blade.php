@extends('layouts.app')

@section('title', 'Domain Search')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Search Domains</h1>
        <form action="{{ route('domains.search') }}" method="GET" class="mb-6">
            <div class="flex space-x-2">
                <input type="text" name="domain" value="{{ $domain ?? '' }}" placeholder="Enter domain name" class="flex-1 p-2 border rounded">
                <select name="extensions[]" multiple class="p-2 border rounded">
                    @foreach (['com', 'net', 'org', 'id', 'co.id', 'web.id'] as $ext)
                        <option value="{{ $ext }}" {{ in_array($ext, $extensions ?? []) ? 'selected' : '' }}>.{{ $ext }}</option>
                    @endforeach
                </select>
                @include('components.button', ['label' => 'Search', 'icon' => 'fas fa-search'])
            </div>
        </form>

        @if (isset($results))
            <h2 class="text-xl font-semibold mb-4">Results</h2>
            <div class="grid gap-4">
                @foreach ($results as $result)
                    <div class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <p class="text-lg font-semibold">{{ $result['domain'] }}</p>
                            <p class="text-gray-600">{{ $result['available'] ? 'Available' : 'Taken' }}</p>
                            @if ($result['available'])
                                <p class="text-blue-600 font-bold">{{ $result['price'] }}</p>
                            @endif
                        </div>
                        @if ($result['available'])
                            <form action="{{ route('domains.reserve') }}" method="POST">
                                @csrf
                                <input type="hidden" name="domain_name" value="{{ explode('.', $result['domain'])[0] }}">
                                <input type="hidden" name="extension" value="{{ $result['extension'] }}">
                                @include('components.button', ['label' => 'Reserve', 'icon' => 'fas fa-lock'])
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
