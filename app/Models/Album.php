<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findorfail($id)
 * @method static create(array $all)
 * @method static paginate(int $int)
 */
class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'artist_id',
        'release_date',
    ];

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }
}
