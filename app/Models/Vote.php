<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'user_id',
        'vote_type',
    ];

    /**
     * Get the user that owns the Vote
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movie that owns the Vote
     *
     * @return BelongsTo
     */
    public function movie() : BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
