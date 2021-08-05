@props(['title'])

@php
    $classes = 'flex-grow p-6 m-5 bg-white shadow-lg rounded-lg opacity-80 hover:opacity-100';
@endphp

{{-- A stats box with title --}}

<div {{ $attributes->merge(['class' => $classes]) }} >

    @if ( strlen($title) < 1 )
        {{ $slot }}
    @else
        <p class="text-2xl text-center">
            <span class="text-sm block text-gray-500">{{ $title }}</span> 
            {{ $slot }}
        </p>        
    @endif

</div>
