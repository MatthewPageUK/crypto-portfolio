@props(['balance'])

@php
    $balance += 0;          // remove trailing zeros
    $text = $balance;
    $pre = "";

    if($balance <= 0.0001) 
    {
        $pre = "< ";
        $text = "0.0001";
    }

    if($balance < 0.1 && $balance > 0.0001) $text = number_format($balance, 6, '.', '');
    if($balance >= 0.1 && $balance < 1) $text = number_format($balance, 5, '.', '');
    if($balance >= 1 && $balance < 100) $text = number_format($balance, 4, '.', '');
    if($balance >= 100 && $balance < 1000) $text = number_format($balance, 2, '.', '');
    if($balance >= 1000) $text = number_format($balance, 0, '.', '');

@endphp

<a title="{{ $balance }}">{{ $pre }} {{ $text + 0 }}</a>
