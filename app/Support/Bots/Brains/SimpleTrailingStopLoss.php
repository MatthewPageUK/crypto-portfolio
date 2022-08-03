<?php

namespace App\Support\Bots\Brains;

use App\Interfaces\BotBrainInterface;
use App\Models\Bot;
use App\Models\BotHistory;
use App\Models\Price;
use App\Support\Prices\PriceService;
use Carbon\Carbon;

/**
 * A bot brain..
 *
 */
class SimpleTrailingStopLoss implements BotBrainInterface
{

    private Bot $bot;

    /**
     * Construct the brain
     *
     */
    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }


    public function processPrice(Price $price)
    {
        $note = "NOP";

        if ($price->price < $this->bot->stop_price) {

            // Rule 1 : Sell on stop loss
            $note = "Stop loss!";
            $this->bot->stopped = Carbon::now();
            $this->bot->save();

        } elseif ($price->price > $this->bot->targetPrice()) {

            // Rule 2 : If above target activate trailing stop

            // Has the stop loss already been moved - already above target
            if($this->bot->stop_price > $this->bot->stopPrice()) {

                // Is this an ATH price for the period of the bot - excluding the current price
                $ath = PriceService::highSince($this->bot->token, $this->bot->created_at, $price);

                if($price->price > $ath) {

                    // Set new stop loss
                    $this->bot->stop_price = $price->price - ( ( $price->price / 100 ) * $this->bot->loss );
                    $note = "Moved trailing stop loss";
                }

            } else {

                // First time over target
                $this->bot->stop_price = $this->bot->targetPrice();
                $note = "Target Hit - trailing stop active";
            }

            $this->bot->save();

        }

        // Remember what happened
        if ($note) {
            $bh = BotHistory::create([
                'bot_id' => $this->bot->id,
                'target_price' => $this->bot->targetPrice(),
                'stop_loss' => $this->bot->stop_price,
                'price' => $price->price,
                'note' => $note,
            ]);
        }

    }

}
