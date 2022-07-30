<?php

namespace App\Cron;

use App\Exceptions\PriceOracleFailureException;
use App\Models\Bot;
use App\Models\BotHistory;
use App\Support\Coinmarketcap;

class UpdateBots
{
    /**
     * Invoke
     *
     */
    public function __invoke()
    {
        $note = '';

        try {
            $cmc = new Coinmarketcap();
            $price = $cmc->getPrice();
        } catch (PriceOracleFailureException $e) {
            $price = 0;
            $note = $e->getMessage();
        }

        foreach (Bot::all() as $bot)
        {
            if ($bot->isRunning()) {

                $bot->touch();

                $bh = BotHistory::create([
                    'bot_id' => $bot->id,
                    'target_price' => $bot->targetPrice(),
                    'stop_loss' => $bot->stopPrice(),
                    'price' => $price,
                    'note' => $note,
                ]);

            }
        }
    }
}
