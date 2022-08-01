<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id',
        'price',
        'note',
    ];

    /**
     * The token this is a price for
     *
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

}
