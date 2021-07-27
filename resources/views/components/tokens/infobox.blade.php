@props(['token'])

@php
    $classes = 'w-full sm:w-64 p-6 m-5 bg-white shadow-lg rounded-lg text-center';
@endphp

{{-- Token Info Box --}}
<div {{ $attributes->merge(['class' => $classes]) }} >

    {{-- Symbol --}}
    <h1 class="text-5xl font-bold text-gray-500">
        <x-tokens.link :token="$token" class="hover:text-yellow-400">
            {{ $token->symbol }}
        </x-tokens.link>
    </h1>

    {{-- Name --}}
    <p class="text-sm mb-4 text-gray-500">
        {{ $token->name }}
    </p>

    {{-- Balance --}}
    <p class="p-2 rounded-lg font-black text-white {{ ( $token->balance()->gt(0) ) ? 'bg-green-600' : 'bg-gray-200' }}">
        <x-quantity :quantity="$token->balance()" />
    </p>

    {{-- Buy / Sell links --}}
    <div class="mt-3 flex">
        <a href="{{ route('token.buy', $token->id) }}" class="flex items-center justify-center flex-grow p-1 text-green-700 hover:text-green-500" title="{{ __('Buy') }} {{ $token->symbol }}">
            <x-icons.plus class="h-6 w-6 inline-block mr-1" />
            {{ __('Buy') }}
        </a>

        @if( $token->balance()->gt(0) )
            <a href="{{ route('token.sell', $token->id) }}" class="flex items-center justify-center flex-grow p-1 text-red-700 hover:text-red-500" title="{{ __('Sell') }} {{ $token->symbol }}">
                <x-icons.minus class="h-6 w-6 inline-block mr-1" />
                {{ __('Sell') }}
            </a>
        @endif
    </div>

</div>