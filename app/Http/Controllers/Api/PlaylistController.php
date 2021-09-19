<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class PlaylistController extends Controller
{
    public function addSong(Song $song): JsonResponse
    {
        auth()->user()->songs()->syncWithoutDetaching($song);

        return response()->json('Song added to playlist');
    }

    public function removeSong(Song $song): JsonResponse
    {
        auth()->user()->songs()->detach($song);

        return response()->json('Song removed from playlist');
    }
}
