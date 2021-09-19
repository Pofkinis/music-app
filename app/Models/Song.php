<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail($id)
 * @method static create(array $all)
 * @method static paginate(int $int)
 * @method static find(mixed $value)
 * @property Collection $comments
 */
class Song extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'album_id',
        'link_to_song',
        'type',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
