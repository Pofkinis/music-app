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

    public function show($id): JsonResponse
    {
        try {
            return response()->json(Song::findorfail($id));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Song not found'
            ], 404);
        }
    }

    public function update(SongRequest $request, $id): JsonResponse
    {
        try {
            $artist = Song::findorfail($id);
            $artist->update($request->all());
            return response()->json($artist);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Song not found'
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Song::findorfail($id)->delete();
            return response()->json('Song has been deleted', 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Song not found'
            ], 404);
        }
    }
}
