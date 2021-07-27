@props(['token'])

@php
    $classes = '';
@endphp

{{-- Link to edit a token --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('token.edit', $token->id) }}" 
    title="{{ __('Edit ') }} {{ $token->name }}"
>
    {{ $slot }}
</a>
