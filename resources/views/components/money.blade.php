@php
    $price = floatval($slot->__toString());
    $decimal = ($price > 100) ? 2 : 4;
@endphp

<span>&pound;{{ number_format($price, $decimal) }}