@props(['balance'])

@php

$text = $balance;
if($balance > 10) $text = number_format($balance, 4);
if($balance > 100) $text = number_format($balance, 2);
if($balance > 1000) $text = number_format($balance, 0);

@endphp

<a title="{{ $balance }}">{{ $text }}</a>
