@props(['transaction'])

@php
    $classes = '';
@endphp

{{-- Link to a transaction --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('transaction.show', $transaction->id) }}" 
    title="{{ __('View transaction') }}"
>
    {{ $slot }}
</a>
