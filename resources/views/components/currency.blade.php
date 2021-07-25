@props(['amount'])

@php
    if( $amount->getValue() < 0 ) $class = "text-red-500";
    else $class = "";
@endphp

<a class="{{ $class }}" title="{{ $amount->getValue() }}">{{ $amount->humanReadable() }}</a>
