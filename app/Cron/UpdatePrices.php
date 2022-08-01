<?php

namespace App\Cron;

use App\Exceptions\PriceOracleFailureException;
use App\Models\Token;
use App\Models\Price;
use App\Support\Coinmarketcap;

class UpdatePrices
{
    /**
     * Invoke
     *
     */
    public function __invoke()
    {
        $note = '';
        $token = Token::where('symbol', 'CHZ')->first();

        // Retrieve the price from the oracle
        try {
            $cmc = new Coinmarketcap();
            $price = $cmc->getPrice();
        } catch (PriceOracleFailureException $e) {
            $price = 0;
            $note = $e->getMessage();
        }

        // Create the price record
        $price = Price::create([
            'token_id' => $token->id,
            'price' => $price,
            'note' => $note,
        ]);

        // Wake up each bot with the new price
        if ($price?->price > 0) {
            foreach ($token->bots as $bot) {
                if ($bot->isRunning()) {
                    $bot->wakeUp($price);
                }
            }
        }

    }
}
