@props(['token'])

@php
    $classes = '';
@endphp

{{-- Link to a token --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('token.show', $token->id) }}" 
    title="{{ __('View') }} {{ $token->name }} {{ __('transactions') }}"
>
    {{ $slot }}
</a>
