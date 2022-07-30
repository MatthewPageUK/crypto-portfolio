<?php

namespace App\Cron;

use App\Exceptions\PriceOracleFailureException;
use App\Models\Bot;
use App\Models\BotHistory;
use App\Support\Coinmarketcap;
use Carbon\Carbon;

class UpdateBots
{
    /**
     * Invoke
     *
     */
    public function __invoke()
    {
        $note = 'NOP';

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

                // Rule 1 : Sell on stop loss
                if($price < $bot->stopPrice()) {
                    $note = "Stop loss!";
                    $bot->stopped = Carbon::now();
                    $bot->save();
                }

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
