@props(['token'])

@php
    $classes = '';
@endphp

{{-- Link to buy a token --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('token.buy', $token->id) }}" 
    title="{{ __('Buy ') }} {{ $token->name }}"
>
    {{ $slot }}
</a>
