<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
