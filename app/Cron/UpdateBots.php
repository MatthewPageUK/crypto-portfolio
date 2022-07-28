<?php

namespace App\Cron;

use App\Models\Bot;

class UpdateBots
{
    /**
     * Invoke
     *
     */
    public function __invoke()
    {
        foreach (Bot::all() as $bot)
        {
            $bot->name = $bot->name . "*";
            $bot->save();
        }
    }

}
