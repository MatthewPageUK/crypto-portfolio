@props(['amount'])

@php
    // $amount += 0;           // remove trailing zeros
    // $text = $amount;
    // $pre = "";

    // if($amount <= 0.0001 && $amount !== 0) 
    // {
    //     $pre = "< ";
    //     $text = "0.0001";
    // }

    // if($amount < 0.1 && $amount > 0.0001) $text = number_format($amount, 6, '.', '');
    // if($amount >= 0.1 && $amount < 1) $text = number_format($amount, 5, '.', '');
    // if($amount >= 1 && $amount < 10) $text = number_format($amount, 4, '.', '');
    // if($amount >= 10 && $amount < 1000) $text = number_format($amount, 2, '.', '');
    // if($amount >= 1000) $text = number_format($amount, 0, '.', '');
@endphp

<a title="{{ $amount }}">{{ $slot }}</a>
