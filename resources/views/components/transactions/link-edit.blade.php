@props(['transaction'])

@php
    $classes = '';
@endphp

{{-- Link to edit a transaction --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('transaction.edit', $transaction->id) }}" 
    title="{{ __('Edit transaction') }}"
>
    {{ $slot }}
</a>
