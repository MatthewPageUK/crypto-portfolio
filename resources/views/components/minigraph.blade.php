@props(['token'])

@php
    $points = "";
    $x = 0;
    $width = 175;

    $transactions = $token->transactions()->get()->where('type', CryptoTransaction::BUY)->slice(0, $width)->reverse();

    if($transactions->count() > 2)
    {
        $maxPrice = $transactions->max('price')->get();
        $minPrice = $transactions->min('price')->get();
        $maxDate = $transactions->max('time');
        $minDate = $transactions->min('time');

        $spacing = ceil(175 / $transactions->count());

        $verticalScale = ( $maxPrice - $minPrice ) / 100;

        foreach($transactions as $transaction)
        {
            $diff = $maxPrice - $transaction->price->get();
            $y = floor( ( $diff / $verticalScale ) / 2);


            $points .= strval($x) . ',' .strval($y).' ';
            $x += $spacing;
        }
    }
@endphp

<div class="mt-2">
    <svg viewBox="0 0 {{ $width }} 50">
        <polyline 
            fill="none"
            stroke="#0074d9"
            stroke-width="1"
            points="{{ $points }}"
        />
    </svg>
    <div class="flex text-xxs mt-2">
        <div class="flex-grow">
            {{ $minDate->format('M \'y') }}
        </div>
        <div class="flex-grow text-right">
            {{ $maxDate->format('M \'y') }}
        </div>
    </div>
</div>