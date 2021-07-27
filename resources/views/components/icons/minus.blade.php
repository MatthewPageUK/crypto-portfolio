@props(['active'])

@php
    $classes = 'h-4 w-4';
@endphp

<svg 
    xmlns="http://www.w3.org/2000/svg" 
    fill="none" 
    viewBox="0 0 24 24" 
    stroke="currentColor"
    {{ $attributes->merge(['class' => $classes]) }}
>
    <path 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        stroke-width="2" 
        d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" 
    />
</svg>
