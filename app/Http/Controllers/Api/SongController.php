<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class SongController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Song::paginate(10));
    }

    public function store(SongRequest $request): JsonResponse
    {
        return response()->json(Song::create($request->all()));
    }

    public function show(Song $song): JsonResponse
    {
        return response()->json($song);
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