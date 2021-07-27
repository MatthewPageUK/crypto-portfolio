@props(['token'])

@php
    $classes = '';
@endphp

{{-- Link to delete a token --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('token.delete', $token->id) }}" 
    title="{{ __('Delete ') }} {{ $token->name }}!"
    onclick="return confirm('Delete this token and ALL transactions now? This can not be undone.')"
>
    {{ $slot }}
</a>
