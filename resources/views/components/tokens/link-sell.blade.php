@props(['token'])

@php
    $classes = '';
@endphp

{{-- Link to sell a token --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('token.sell', $token->id) }}" 
    title="{{ __('Sell ') }} {{ $token->name }}"
>
    {{ $slot }}
</a>
