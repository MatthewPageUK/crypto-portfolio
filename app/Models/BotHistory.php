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

class BotHistory extends Model
{
    use SoftDeletes;
    use HasTimestamps;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'target_price',
        'stop_loss',
        'price',
        'note',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

}
