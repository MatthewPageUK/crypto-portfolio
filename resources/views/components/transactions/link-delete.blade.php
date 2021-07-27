@props(['transaction'])

@php
    $classes = '';
@endphp

{{-- Link to delete a transaction --}}
<a 
    {{ $attributes->merge(['class' => $classes]) }}
    href="{{ route('transaction.delete', $transaction->id) }}" 
    title="{{ __('Delete transaction') }}!"
    onclick="return confirm('Delete this transactions now? This can not be undone.')"
>
    {{ $slot }}
</a>
