<?php

namespace App\Models;

use App\Support\Currency;
use App\Support\Quantity;
use App\Interfaces\TokenInterface;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bot extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'direction',
        'token_id',
        'price',
        'quantity',
        'profit',
        'loss',
        'status',
        'started',
        'stopped',
    ];

    /**
     * Start the bot running
     *
     */
    public function start()
    {
        $this->started = \Carbon\Carbon::now();
        $this->save();

    }

    /**
     * Stop the bot running
     *
     */
    public function stop()
    {
        $this->stopped = \Carbon\Carbon::now();
        $this->save();

    }

    /**
     * The token this bot is trading
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    /**
     * The bot history
     */
    public function history(): hasMany
    {
        return $this->hasMany(BotHistory::class);
    }

    /**
     * Is this bot running now ?
     *
     */
    public function isRunning()
    {
        return $this->started !== null;
    }

    /**
     * Is this bot stopped now ?
     *
     */
    public function isStopped()
    {
        return $this->stopped !== null;
    }
    /**
     * History for this bot's actions
     *
     * @return HasMany
     */
    // public function botHistory(): HasMany
    // {
    //     return $this->hasMany(BotHistory::class)->orderByDesc('created_at');
    // }



    public function targetPrice()
    {
        return $this->price + ( ( $this->price / 100 ) * $this->profit );
    }
    public function stopPrice()
    {
        return $this->price - ( ( $this->price / 100 ) * $this->loss );
    }
    public function getCurrentValue()
    {
        $lastPrice = $this->history->last()?->price;

        return $lastPrice * $this->quantity;
    }
    public function getProfitLoss()
    {
        $lastPrice = $this->history->last()?->price;

        return $this->getCurrentValue() - ( $this->price * $this->quantity );
    }
}
