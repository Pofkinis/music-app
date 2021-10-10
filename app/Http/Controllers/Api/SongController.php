<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin'])->only(['store', 'update', 'destroy']);
    }

    public function getSongByArtist(Artist $artist)
    {
        return response()->json($artist->albums()->paginate(10));
    }

    public function index(): JsonResponse
    {
        return response()->json(Song::with(['album', 'album.artist'])->paginate(10));
    }

    public function store(SongRequest $request): JsonResponse
    {
        return response()->json(Song::create($request->all()));
    }

    public function show(Song $song): JsonResponse
    {
        return response()->json($song->load(['album', 'album.artist']));
    }

    public function update(SongRequest $request, Song $song): JsonResponse
    {
        $song->update($request->all());
        return response()->json($song);
    }

    public function destroy(Song $song): JsonResponse
    {
        $song->delete();
        return response()->json('Song has been deleted', 204);
    }
}
