<?php

namespace App\Support\Prices;

use App\Models\Price;
use App\Models\Token;
use Carbon\Carbon;

/**
 * Price services
 *
 */
class PriceService
{

    public static function latest(Token $token): float
    {
        $price = Price::where('token_id', $token->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $price ? $price->price : -1;
    }

    public static function highSince(Token $token, Carbon $from, Price $exclude = null)
    {
        if($exclude) {
            $price = Price::where('token_id', $token->id)
            ->where('created_at', '>', $from)
            ->where('id', '!=', $exclude->id)
            ->orderBy('price', 'desc')
            ->first();
        } else {
            $price = Price::where('token_id', $token->id)
            ->where('created_at', '>', $from)
            ->orderBy('price', 'desc')
            ->first();
        }

        return $price ? $price->price : -1;
    }

}