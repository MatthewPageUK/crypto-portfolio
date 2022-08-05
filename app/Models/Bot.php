<?php

namespace App\Models;

use App\Interfaces\BotBrainInterface;
use App\Support\Prices\PriceService;
use Carbon\Carbon;
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
     * The Bots Brain type
     *
     * @var string;
     */
    private $brain = 'App\Support\Bots\Brains\SimpleTrailingStopLoss';

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
        'stop_price',
        'status',
        'started',
        'stopped',
    ];

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
     * Buy some crypto
     *
     */
    public function buy($quantity, Price $price)
    {
        // Call exchange API

    }

    /**
     * Sell some crypto
     *
     */
    public function sell($quantity, Price $price)
    {
        // Call exchange API

    }

    public function wakeUp(Price $price)
    {
        if ($this->isRunning()) {
            $brain = new ($this->brain)($this);
            $brain->processPrice($price);
        }
    }

    /**
     * Is this bot running now ?
     *
     */
    public function isRunning()
    {
        return $this->started !== null && $this->stopped === null;
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
     * The initial target price
     *
     * @return float
     */
    public function targetPrice()
    {
        return $this->price + ( ( $this->price / 100 ) * $this->profit );
    }

    /**
     * The initial stop price
     *
     * @return float
     */
    public function stopPrice()
    {
        return $this->price - ( ( $this->price / 100 ) * $this->loss );
    }

    /**
     * Current value of tokens held based on last price
     *
     * @return float
     */
    public function getCurrentValue()
    {
        $lastPrice = $this->history->last()?->price;

        return $lastPrice * $this->quantity;
    }

    /**
     * How much exposure this bot has (how much we put in)
     *
     * @return float
     */
    public function getExposure()
    {
        return $this->price * $this->quantity;
    }

    /**
     * How much profit or loss are we in based on last price
     *
     * @return float
     */
    public function getProfitLoss()
    {
        return $this->getCurrentValue() - $this->getExposure();
    }

    /**
     * The potential gains if target is hit
     *
     * @return float
     */
    public function getGain()
    {
        return ( $this->getExposure() / 100 ) * $this->profit;
    }

    /**
     * The potential risk if stop loss is triggered
     *
     * @return float
     */
    public function getRisk()
    {
        return ( $this->getExposure() / 100 ) * $this->loss;
    }

    /**
     * Get the animal type :)
     *
     * @return string
     */
    public function getAnimal()
    {
        return $this->direction === 'up' ? 'Bull' : 'Bear';
    }

    /**
     * Start the bot running
     *
     * @return void
     */
    public function start(): void
    {
        $this->started = \Carbon\Carbon::now();
        $this->save();
    }

    /**
     * Stop the bot running
     *
     * @return void
     */
    public function stop(): void
    {
        $this->stopped = \Carbon\Carbon::now();
        $this->save();
    }
}
