<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $all)
 * @property integer $user_id
 */
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'comment',
        'user_id',
        'song_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
