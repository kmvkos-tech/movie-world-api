<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'publication_date'
    ];


    /**
     * Get the user that owns the Movie
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the votes for the Movie
     *
     * @return HasMany
     */
    public function votes() : HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the likes for the Movie
     *
     * @return HasMany
     */
    public function likes() : HasMany
    {
        return $this->votes()->where('vote_type', 'like');
    }

    /**
     * Get the hates for the Movie
     *
     * @return HasMany
     */
    public function hates() : HasMany
    {
        return $this->votes()->where('vote_type', 'hate');
    }
}
